<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'name',
        'usuario',
        'type',               // columna con CHECK (de la BD)
        'type_sis_permiso',   // tu rol interno (admin, seguridad, etc.)
        'area_responsable',
        'email',
        'telephone',          // TELÉFONO
        'number',             // DNI
        'password',
        'active',
        'api_token',
         'IND_GERENTE',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'active' => 'boolean',
    ];
    // App\Models\User.php

 
}
