<?php

namespace App\Http\Controllers\Luzcard;

use App\Http\Controllers\Controller;
use App\Models\RegistroAfiliado;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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

    public function generarContratoPdf($afiliado)
    {
        // Calculamos la fecha de fin (1 año después de la fecha de registro) 
        $fechaFin = \Carbon\Carbon::parse($afiliado->Fecha_Registro)->addYear()->format('d/m/Y');

        // Cargar la vista con los datos
        $pdf = Pdf::loadView('registros.pdf.contrato_luzcard', compact('afiliado', 'fechaFin'));

        // Definir nombre y ruta del archivo
        $nombreArchivo = "Contrato_LuzCard_{$afiliado->Afiliado_DNI}_" . time() . ".pdf";
        $rutaCarpeta = 'contratos_generados';
        $rutaCompleta = "{$rutaCarpeta}/{$nombreArchivo}";

        // Almacenar el PDF en el disco 'public' (storage/app/public/contratos_generados)
        \Illuminate\Support\Facades\Storage::disk('public')->put($rutaCompleta, $pdf->output());

        // Guardar la ruta en la base de datos
        $afiliado->update([
            'Ruta_Contrato' => $rutaCompleta
        ]);

        return $pdf->download($nombreArchivo);
    }
    public function create()
    {

        return view('registros.create');
    }
    public function reimprimir($id)
    {
        $afiliado = RegistroAfiliado::findOrFail($id);

        // Si ya tiene ruta, descargamos el archivo existente
        if ($afiliado->Ruta_Contrato && Storage::disk('public')->exists($afiliado->Ruta_Contrato)) {
            return Storage::disk('public')->download($afiliado->Ruta_Contrato);
        }

        // Si no existe, lo generamos usando tu función actual
        return $this->generarContratoPdf($afiliado);
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
            $data['Estado_Registro'] = 'ACT';
            $data['Fecha_Registro'] = $request->input('Fecha_Registro', now()->format('Y-m-d'));
            $data['Tiene_Firma_Huella'] = $request->has('Tiene_Firma_Huella') ? 1 : 0;
            $data['Orientador'] = $request->input('Orientador');
            $afiliado = RegistroAfiliado::create($data);

            // Generar PDF del contrato
            if ($request->has('generar_pdf')) {
                return $this->generarContratoPdf($afiliado);
            }
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
    /**
 * Actualiza el registro de un afiliado existente
 */
public function update(Request $request, $id)
{
    // 1. Validar datos
    $request->validate([
        'Afiliado_Nombres'   => 'required|string|max:150',
        'Afiliado_DNI'       => 'required|digits:8',
        'Afiliado_Telefono'  => 'nullable|string|max:20',
        'Afiliado_Email'     => 'nullable|email|max:100',
        'Fecha_Registro'     => 'required|date',
        'Contrato_adjunto'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    $afiliado = RegistroAfiliado::findOrFail($id);

    DB::beginTransaction();
    try {
        // Obtenemos todos los datos excepto los restringidos
        $data = $request->except([
            'fecha_ini_vigencia', 
            'fecha_fin_vigencia', 
            'producto', 
            'boleta', 
            'total'
        ]);

        // Manejo de nuevo archivo si se sube uno
        if ($request->hasFile('Contrato_adjunto')) {
            // Opcional: Eliminar el archivo anterior si existe
            if ($afiliado->Contrato_adjunto) {
                Storage::disk('public')->delete($afiliado->Contrato_adjunto);
            }
            $data['Contrato_adjunto'] = $request->file('Contrato_adjunto')->store('contratos', 'public');
        }

        // Checkbox de firma
        $data['Tiene_Firma_Huella'] = $request->has('Tiene_Firma_Huella') ? 1 : 0;

        $afiliado->update($data);

        DB::commit();
        return redirect()->route('dashboard')->with('success', 'Registro actualizado correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error Update LUZCARD: " . $e->getMessage());
        return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
}
}
