<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetTenantConnection
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si hay una conexión guardada en la sesión (puesto por AdminController@login)
        if (Session::has('db_connection')) {
            $connection = Session::get('db_connection');

            // 1. Configurar la conexión por defecto en tiempo de A
            Config::set('database.default', $connection);

            // 2. Purgar y reconectar para asegurar que se use la nueva config
            DB::purge($connection);
            DB::reconnect($connection);
        }

        return $next($request);
    }
}