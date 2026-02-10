<?php

namespace App\Http\Controllers\Luzcard;

use App\Http\Controllers\Controller;
use App\Models\RegistroAfiliado;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistroWebController extends Controller
{
    /**
     * Muestra la lista de pacientes (Web)
     */
    public function index(Request $request)
    {
        $query = RegistroAfiliado::query();

        // Buscador simple
        if ($request->has('buscar') && $request->buscar != '') {
            $busqueda = $request->buscar;
            $query->where(function ($q) use ($busqueda) {
                $q->where('Afiliado_DNI', 'like', "%$busqueda%")
                    ->orWhere('Afiliado_Nombres', 'like', "%$busqueda%");
            });
        }

        // Paginación de 10 en 10, ordenado por el más reciente
        $registros = $query->orderBy('ID_Registro', 'desc')->paginate(10);
        $vendedores = Vendedor::where('IND_BAJA', 'N')->get();

        return view('registros.index', compact('registros', 'vendedores'));
    }

    /**
     * Muestra el formulario de creación (Página completa, no modal)
     */
    public function create()
    {

        return view('registros.create');
    }

    /**
     * Guarda el registro
     */
    public function store(Request $request)
    {
        // 1. Validar
        $request->validate([
            'Afiliado_Nombres'   => 'required|string|max:150',
            'Afiliado_DNI'       => 'required|digits:8',
            'Afiliado_Telefono'  => 'nullable|string|max:20',
            'Afiliado_Email'     => 'nullable|email|max:100',
            'Fecha_Registro' => 'required|date',
            'Contrato_adjunto'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            // 2. Manejo de Archivo
            if ($request->hasFile('Contrato_adjunto')) {
                // Guarda en storage/app/public/contratos
                $path = $request->file('Contrato_adjunto')->store('contratos', 'public');
                $data['Contrato_adjunto'] = $path;
            }

            // 3. Defaults
            $data['Fecha_Registro'] = now();
            $data['Estado_Registro'] = 'ACT';
            $data['Fecha_Registro'] = $request->input('Fecha_Registro', now()->format('Y-m-d'));
            $data['Tiene_Firma_Huella'] = $request->has('Tiene_Firma_Huella') ? 1 : 0;
            $data['Orientador'] = Auth::user()->usuario;
            $data['fecha_ini_vigencia'] =  $request->input('Fecha_Registro', now()->format('Y-m-d'));
            $data['fecha_fin_vigencia'] = now()->addYear()->format('Y-m-d');

            RegistroAfiliado::create($data);

            DB::commit();

            // Redirigir al index con mensaje de éxito
            return redirect()->route('dashboard')->with('success', 'Afiliado registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error Web LUZCARD: " . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar: ' . $e->getMessage());
        }
    }
}
