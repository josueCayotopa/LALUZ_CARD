<?php

namespace App\Http\Controllers\Luzcard;

use App\Http\Controllers\Controller;
use App\Models\RegistroAfiliado;
use App\Mail\TarjetaLuzCardMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EnvioCorreoController extends Controller
{
    /**
     * Generar imagen de la tarjeta digital con los datos del afiliado
     */
    private function generarImagenTarjeta($afiliado)
    {
        try {
            // Ruta de la imagen base (tu tarjeta diseñada)
            $imagenBase = public_path('images/tarjeta-base-luzcard.png');

            // Si no existe la imagen base, retornar null
            if (!file_exists($imagenBase)) {
                Log::warning("No se encontró la imagen base de la tarjeta en: {$imagenBase}");
                return null;
            }

            // Crear imagen desde PNG
            $imagen = imagecreatefrompng($imagenBase);

            if (!$imagen) {
                Log::error("No se pudo crear la imagen desde: {$imagenBase}");
                return null;
            }

            // Definir color blanco para el texto
            $colorBlanco = imagecolorallocate($imagen, 255, 255, 255);

            // Ruta de la fuente (opcional - si tienes una fuente TTF)
            $fuente = public_path('fonts/Arial-Bold.ttf');

            // Agregar texto a la tarjeta
            if (file_exists($fuente)) {
                // Con fuente TTF (más bonito)
                imagettftext(
                    $imagen,
                    18,
                    0,
                    320,
                    165,
                    $colorBlanco,
                    $fuente,
                    strtoupper($afiliado->Afiliado_Nombres)
                );
                imagettftext(
                    $imagen,
                    16,
                    0,
                    320,
                    190,
                    $colorBlanco,
                    $fuente,
                    'DNI: ' . $afiliado->Afiliado_DNI
                );

                // Fecha de vigencia
                $fechaVigencia = $afiliado->fecha_fin_vigencia
                    ? \Carbon\Carbon::parse($afiliado->fecha_fin_vigencia)->format('d/m/Y')
                    : \Carbon\Carbon::parse($afiliado->Fecha_Registro)->addYear()->format('d/m/Y');
                imagettftext($imagen, 14, 0, 520, 267, $colorBlanco, $fuente, $fechaVigencia);
            } else {
                // Sin fuente TTF (más simple, usando fuentes built-in)
                imagestring($imagen, 5, 320, 155, strtoupper($afiliado->Afiliado_Nombres), $colorBlanco);
                imagestring($imagen, 5, 320, 180, 'DNI: ' . $afiliado->Afiliado_DNI, $colorBlanco);

                $fechaVigencia = $afiliado->fecha_fin_vigencia
                    ? \Carbon\Carbon::parse($afiliado->fecha_fin_vigencia)->format('d/m/Y')
                    : \Carbon\Carbon::parse($afiliado->Fecha_Registro)->addYear()->format('d/m/Y');
                imagestring($imagen, 4, 520, 257, $fechaVigencia, $colorBlanco);
            }

            // Definir ruta de salida
            $nombreArchivo = 'tarjeta_' . $afiliado->Afiliado_DNI . '_' . time() . '.png';
            $carpetaSalida = storage_path('app/public/tarjetas_generadas');

            // Crear directorio si no existe
            if (!file_exists($carpetaSalida)) {
                mkdir($carpetaSalida, 0755, true);
            }

            $rutaSalida = $carpetaSalida . '/' . $nombreArchivo;

            // Guardar imagen
            $guardado = imagepng($imagen, $rutaSalida);
            imagedestroy($imagen);

            if ($guardado) {
                Log::info("Tarjeta generada exitosamente: {$rutaSalida}");
                return $rutaSalida;
            } else {
                Log::error("Error al guardar la imagen en: {$rutaSalida}");
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Error al generar imagen de tarjeta: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Enviar tarjeta por correo electrónico (individual)
     */
    public function enviarTarjeta(Request $request, $id)
    {
        try {
            $afiliado = RegistroAfiliado::findOrFail($id);

            if (!$afiliado->Afiliado_Email) {
                return back()->with('error', 'El afiliado no tiene un correo electrónico registrado.');
            }

            $rutaTarjeta = $this->generarImagenTarjeta($afiliado);

            // El envío se procesa aquí. Si falla, el catch lo atrapará
                    Mail::to($afiliado->Afiliado_Email)->send(new TarjetaLuzCardMail($afiliado, $rutaTarjeta));

            Log::info("Tarjeta enviada exitosamente a: {$afiliado->Afiliado_Email}");
            return back()->with('success', '✅ ¡Tarjeta enviada exitosamente a ' . $afiliado->Afiliado_Email . '!');
        } catch (\Exception $e) {
            Log::error("Error al enviar tarjeta: " . $e->getMessage());
            return back()->with('error', 'Error al enviar la tarjeta: ' . $e->getMessage());
        }
    }
    /**
     * Enviar tarjetas masivamente a todos los afiliados activos
     */
    public function enviarTarjetasMasivo(Request $request)
    {
        try {
            // Obtener afiliados activos con email
            $afiliados = RegistroAfiliado::where('Estado_Registro', 'ACT')
                ->whereNotNull('Afiliado_Email')
                ->where('Afiliado_Email', '!=', '')
                ->get();

            if ($afiliados->isEmpty()) {
                return back()->with('warning', 'No hay afiliados con email para enviar.');
            }

            $enviados = 0;
            $errores = 0;
            $erroresDetalle = [];

            foreach ($afiliados as $afiliado) {
                try {
                    // Generar imagen de la tarjeta
                    $rutaTarjeta = $this->generarImagenTarjeta($afiliado);

                    // Enviar correo
                    Mail::to($afiliado->Afiliado_Email)->send(new TarjetaLuzCardMail($afiliado, $rutaTarjeta));

                    if (count(Mail::failures()) == 0) {
                        $enviados++;
                        Log::info("Tarjeta enviada a: {$afiliado->Afiliado_Email}");
                    } else {
                        $errores++;
                        $erroresDetalle[] = $afiliado->Afiliado_Email;
                        Log::error("Fallo al enviar a: {$afiliado->Afiliado_Email}");
                    }

                    // Pausa de 1 segundo para no saturar el servidor SMTP
                    sleep(1);
                } catch (\Exception $e) {
                    $errores++;
                    $erroresDetalle[] = $afiliado->Afiliado_Email;
                    Log::error("Error al enviar tarjeta a {$afiliado->Afiliado_Email}: " . $e->getMessage());
                }
            }

            $mensaje = "📊 Envío masivo completado: {$enviados} enviados, {$errores} errores.";

            if ($errores > 0) {
                $mensaje .= " Emails con error: " . implode(', ', $erroresDetalle);
            }

            return back()->with($errores > 0 ? 'warning' : 'success', $mensaje);
        } catch (\Exception $e) {
            Log::error("Error en envío masivo: " . $e->getMessage());
            return back()->with('error', 'Error en el envío masivo: ' . $e->getMessage());
        }
    }

    /**
     * Vista previa del correo (para pruebas - sin enviar)
     */
    public function previsualizarCorreo($id)
    {
        $afiliado = RegistroAfiliado::findOrFail($id);

        return view('emails.tarjeta-luzcard', [
            'nombre' => $afiliado->Afiliado_Nombres,
            'dni' => $afiliado->Afiliado_DNI,
            'fechaRegistro' => \Carbon\Carbon::parse($afiliado->Fecha_Registro)->format('d/m/Y'),
            'fechaVigencia' => $afiliado->fecha_fin_vigencia
                ? \Carbon\Carbon::parse($afiliado->fecha_fin_vigencia)->format('d/m/Y')
                : \Carbon\Carbon::parse($afiliado->Fecha_Registro)->addYear()->format('d/m/Y'),
        ]);
    }

    /**
     * Enviar correo de prueba (para verificar configuración)
     */
    public function enviarPrueba(Request $request)
    {
        try {
            $email = $request->input('email', auth()->user()->email ?? 'test@example.com');

            Mail::raw('✅ ¡Configuración de correo funcionando correctamente! - LUZCARD', function ($message) use ($email) {
                $message->to($email)
                    ->subject('🧪 Prueba de Correo - LUZCARD');
            });

            if (count(Mail::failures()) > 0) {
                return back()->with('error', '❌ Error al enviar correo de prueba. Revisa la configuración.');
            }

            return back()->with('success', "✅ Correo de prueba enviado a: {$email}");
        } catch (\Exception $e) {
            Log::error("Error en prueba de correo: " . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
