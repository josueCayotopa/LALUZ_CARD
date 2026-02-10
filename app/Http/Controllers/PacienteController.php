<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function buscarPorDni($dni)
    {
        $paciente = Paciente::where('NUM_HC', $dni)->first();

        if (!$paciente) {
            return response()->json(['error' => 'Paciente no encontrado'], 404);
        }

        return response()->json([
            'nombres' => $paciente->NOM_PACIENTE . ' ' . $paciente->APE_PATERNO . ' ' . $paciente->APE_MATERNO,
            'dni' => $paciente->NUM_HC,
            // Agrega aquí otros campos si los necesitas (email, dirección, etc)
        ]);
    }
}
