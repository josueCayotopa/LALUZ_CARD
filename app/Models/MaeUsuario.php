<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;

// Cambia "extends TenantModel" por "extends Authenticatable"
class MaeUsuario extends Authenticatable
{

    use  Notifiable;

    protected $table = 'MAE_USUARIO';

    public $timestamps = false;

    /**
     * La clave primaria del modelo.
     *
     * @var string
     */
    protected $primaryKey = 'COD_USUARIO';

    /**
     * Indica si la clave primaria es auto-incrementable.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * El tipo de la clave primaria.
     *
     * @var string
     */
    protected $keyType = 'string';

    protected $fillable = [
        'COD_USUARIO',
        'COD_EMPRESA',
        'DES_PASSWORD',
        'IND_BAJA',
        'FECHA_BAJA',
        'NOM_USUARIO',
        'IND_ADMIN',
        'COD_PERSONAL',
        'IND_INTRANET',
        'FEC_CREACION',
        'FIRMA_DIGITAL',
        'FIRMA_FECHA_SUBIDA',
        'IND_GERENTE', // Agregado indicador de gerente
    ];

    /**
     * Los atributos que deben ocultarse para las matrices.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'DES_PASSWORD',
        'remember_token',
    ];

    /**
     * Obtiene el nombre de la columna de contraseña para autenticación.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->DES_PASSWORD;
    }


    public function getRememberTokenName()
    {
        return null;
    }
    public function setRememberToken($value) {}
    public function getRememberToken()
    {
        return null;
    }
}
