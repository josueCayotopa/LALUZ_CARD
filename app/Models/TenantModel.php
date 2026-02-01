<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

abstract class TenantModel extends Model
{
    /** Siempre usa la conexión de la empresa guardada en sesión */
    public function getConnectionName()
    {
        return Session::get('db_connection') ?: Config::get('database.default');
    }
}