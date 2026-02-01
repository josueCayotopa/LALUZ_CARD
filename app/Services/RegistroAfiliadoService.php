<?php

namespace App\Services;

use App\Models\RegistroAfiliado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class RegistroAfiliadoService
{
    /**
     * Crear un nuevo registro de afiliado.
     *
     * @param array $data Los datos validados del request
     * @return RegistroAfiliado
     * @throws Exception
     */
    public function crearRegistro(array $data)
    {
        // Iniciamos una transacción para asegurar integridad, 
        // útil si luego agregas más pasos (ej: guardar logs o tablas relacionadas)
        DB::beginTransaction();

        try {
            // Lógica opcional: Si 'Contrato_adjunto' es un archivo subido (UploadedFile),
            // aquí podrías guardarlo en el Storage y obtener la ruta.
            if (isset($data['Contrato_adjunto']) && $data['Contrato_adjunto'] instanceof \Illuminate\Http\UploadedFile) {
                // Ejemplo: guardar en carpeta 'contratos'
                $path = $data['Contrato_adjunto']->store('contratos', 'public'); 
                $data['Contrato_adjunto'] = $path;
            }

            // Crear el registro
            $registro = RegistroAfiliado::create([
                'Orientador'           => $data['Orientador'] ?? null, // Puede venir del Auth::user()->name
                'Afiliado_Nombres'     => $data['Afiliado_Nombres'],
                'Afiliado_DNI'         => $data['Afiliado_DNI'],
                'Afiliado_Telefono'    => $data['Afiliado_Telefono'] ?? null,
                'Afiliado_Direccion'   => $data['Afiliado_Direccion'] ?? null,
                'Afiliado_Email'       => $data['Afiliado_Email'] ?? null,
                
                // Datos Apoderado
                'Apoderado_Parentesco' => $data['Apoderado_Parentesco'] ?? null,
                'Apoderado_Nombres'    => $data['Apoderado_Nombres'] ?? null,
                'Apoderado_DNI'        => $data['Apoderado_DNI'] ?? null,
                'Apoderado_Telefono'   => $data['Apoderado_Telefono'] ?? null,
                'Apoderado_Direccion'  => $data['Apoderado_Direccion'] ?? null,
                'Apoderado_Email'      => $data['Apoderado_Email'] ?? null,

                // Control
                'Tiene_Firma_Huella'   => $data['Tiene_Firma_Huella'] ?? 0,
                'Contrato_adjunto'     => $data['Contrato_adjunto'] ?? null,
                'Estado_Registro'      => 'ACT', // Forzamos ACT al crear
            ]);

            DB::commit();

            return $registro;

        } catch (Exception $e) {
            DB::rollBack();
            // Loguear el error para depuración interna
            Log::error("Error creando registro LUZCARD: " . $e->getMessage());
            throw new Exception("No se pudo procesar el registro del afiliado.");
        }
    }
}