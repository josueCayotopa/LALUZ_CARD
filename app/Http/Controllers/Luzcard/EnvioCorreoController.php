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

    private function generarImagenTarjeta($afiliado)
    {
        try {
            $imagenBase = public_path('images/fondo-tarjeta.png');
            $fuente = public_path('fonts/Arial-Bold.ttf');

            if (!file_exists($imagenBase)) {
                Log::error("Falta imagen base en: " . $imagenBase);
                return null;
            }

            $imagen = imagecreatefrompng($imagenBase);
            $colorBlanco = imagecolorallocate($imagen, 255, 255, 255);

            // VERIFICACIÓN CRÍTICA DE FUENTE
            if (file_exists($fuente)) {
                // 1. NOMBRE DEL PACIENTE
                // X: Bajamos a 480 para moverlo más a la izquierda
                // Y: 430 para centrarlo verticalmente en la zona media
                imagettftext($imagen, 22, 0, 480, 430, $colorBlanco, $fuente, strtoupper($afiliado->Afiliado_Nombres));

                // 2. DNI
                // X: 480 (Alineado con el nombre para que se vea ordenado)
                // Y: 480 (Espaciado uniforme debajo del nombre)
                imagettftext($imagen, 20, 0, 480, 480, $colorBlanco, $fuente, 'DNI: ' . $afiliado->Afiliado_DNI);

                // 3. FECHA DE VALIDEZ
                // Calculamos la fecha (1 año después del registro si no tiene fin de vigencia)
                $fechaVigencia = $afiliado->fecha_fin_vigencia
                    ? \Carbon\Carbon::parse($afiliado->fecha_fin_vigencia)->format('d / m / y')
                    : \Carbon\Carbon::parse($afiliado->Fecha_Registro)->addYear()->format('d / m / y');

                // "VÁLIDA HASTA" - Lo movemos también a la izquierda (X: 480) pero más abajo (Y: 780)
                imagettftext($imagen, 12, 0, 480, 530, $colorBlanco, $fuente, "VÁLIDA HASTA  :");

                // La fecha centrada al lado del texto anterior
                imagettftext($imagen, 18, 0, 600, 530, $colorBlanco, $fuente, $fechaVigencia);
            } else {
                // FALLBACK: Si no hay fuente TTF, usar fuente básica de sistema para no enviar la tarjeta vacía
                Log::warning("Fuente no encontrada en $fuente. Usando imagestring.");
                imagestring($imagen, 5, 580, 480, strtoupper($afiliado->Afiliado_Nombres), $colorBlanco);
                imagestring($imagen, 5, 580, 520, 'DNI: ' . $afiliado->Afiliado_DNI, $colorBlanco);
            }

            $nombreArchivo = 'tarjeta_' . $afiliado->Afiliado_DNI . '_' . time() . '.png';
            $carpetaSalida = storage_path('app/public/tarjetas_generadas');

            if (!file_exists($carpetaSalida)) {
                mkdir($carpetaSalida, 0755, true);
            }

            $rutaSalida = $carpetaSalida . '/' . $nombreArchivo;
            imagepng($imagen, $rutaSalida);
            imagedestroy($imagen);

            return $rutaSalida;
        } catch (\Exception $e) {
            Log::error("Error en LuzCard: " . $e->getMessage());
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
        $ruta = $this->generarImagenTarjeta($afiliado);
        return response()->file($ruta); // Esto te abrirá solo la imagen en el navegador
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
