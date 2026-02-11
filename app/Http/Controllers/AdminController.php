<?php

namespace App\Http\Controllers;

use App\Models\MaeUsuario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    /**
     * Mapeo de nombres de empresas a bases de datos
     */
    protected $empresaToDB = [
        'EMPRESA_PRUEBAS' => 'BDV0004_PRUEBA',
        'CLINICA LA LUZ SAC' => 'BDV0004',
        'INVERSIONES LOS CAPULLOS' => 'CAPULLOS',
        'CLINICA LA LUZ JAEN' => 'CLJAEN',
        'CLINICA LA LUZ OFTALMOLOGIA - BREÑA' => 'CLOFTALMO',
        'INSTITUTO OFTALMOLOGICO LA LUZ' => 'IOLL',
        'CLINICA LA LUZ TACNA' => 'CLTACNA_TEST',
        'SELUCE' => 'SELUCE',
        'ETEL MEDIC' => 'ETEL',
    ];

    /**
     * Mapeo de nombres de empresas a conexiones de base de datos
     * Para pruebas, todas las empresas usan la misma conexión
     */
    protected $empresaToConnection = [
        'EMPRESA_PRUEBAS' => 'BDV0004_PRUEBA',
        'CLINICA LA LUZ SAC' => 'BDV0004',
        'INVERSIONES LOS CAPULLOS' => 'CAPULLOS',
        'CLINICA LA LUZ JAEN' => 'CLJAEN',
        'CLINICA LA LUZ OFTALMOLOGIA - BREÑA' => 'CLOFTALMO',
        'INSTITUTO OFTALMOLOGICO LA LUZ' => 'IOLL',
        'CLINICA LA LUZ TACNA' => 'CLTACNA_TEST',
        'SELUCE' => 'SELUCE',
        'ETEL MEDIC' => 'ETEL',
    ];
    /**
     * Muestra el formulario de Login.
     */
    function parseoPass($clave)
    {
        $as_cadena_ing = $clave;
        $il_longi   = 0;
        $il_count   = 0;
        $il_suma = 0;
        $il_base = 0;
        $vl_cadena_conv = '';
        $as_cadena_dev = '';

        //$il_longi = truncate_float( (strlen($as_cadena_ing)/2), 0);
        $il_longi = floor(strlen($as_cadena_ing) / 2);

        $vl_cadena_conv = substr($as_cadena_ing, $il_longi * -1) . $as_cadena_ing . substr($as_cadena_ing, 0, $il_longi);

        $il_longi = strlen($vl_cadena_conv);
        $il_count = 0; //$il_count = 1;
        $il_suma = 0;
        $sum = 0;

        do {
            $sub = substr($vl_cadena_conv, $il_count, 1);
            $il_suma = $il_suma + ord($sub);

            $il_count = $il_count + 1;
        } while ($il_count < $il_longi);

        $il_base = floor($il_suma / $il_longi);
        $il_count = 0;

        do {
            $sub = substr($vl_cadena_conv, $il_count, 1); //echo $sub;
            $as_cadena_dev .= chr(ord($sub) + $il_base);

            $il_count = $il_count + 1;
        } while ($il_count < $il_longi);
        //echo PHP_EOL;
        $as_cadena_dev = chr($il_base - 15) . $as_cadena_dev . chr(2 * $il_base);
        return utf8_encode($as_cadena_dev);
    }


    /**
     * Verifica la contraseña cifrada.
     */
    public function verifyPassword($inputPassword, $storedPassword)
    {


        if (empty($inputPassword) || empty($storedPassword)) {
            return false;
        }

        //  depuración, puedes registrar los valores
        Log::info('Input password: ' . $inputPassword);
        Log::info('Encrypted input: ' . $this->parseoPass($inputPassword));
        Log::info('Stored password: ' . $storedPassword);

        return $this->parseoPass($inputPassword) === $storedPassword;
    }
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Procesa el intento de Login.
     */



    public function login(Request $request)
    {
        $request->validate([
            'empresa'     => 'required',
            'nom_usuario' => 'required',
            'password'    => 'required',
        ]);

        $connectionKey = $this->empresaToConnection[$request->empresa];
        Config::set('database.default', $connectionKey);
        DB::purge($connectionKey);

        try {
            $mae = MaeUsuario::where('COD_USUARIO', $request->nom_usuario)->first();

            if (!$mae || !$this->verifyPassword($request->password, $mae->DES_PASSWORD)) {
                throw ValidationException::withMessages([
                    'nom_usuario' => ['Credenciales incorrectas en el sistema de la clínica.'],
                ]);
            }

            // LOGUEO DIRECTO USANDO EL MODELO DE LA CLÍNICA
            Auth::login($mae);

            $request->session()->regenerate();
            Session::put('empresa', $request->empresa);
            Session::put('db_connection', $connectionKey);

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            return back()->withErrors(['nom_usuario' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Cierra la sesión.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Muestra la vista principal (Dashboard) donde está la tabla y formulario.
     */
    public function index()
    {
        // Retorna la vista que contiene tu HTML/JS
        // Pasamos el usuario para mostrar su nombre en el navbar
        return view('registros.index', ['user' => Auth::user()]);
    }
}
