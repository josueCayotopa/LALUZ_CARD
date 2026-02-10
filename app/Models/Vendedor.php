<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
     use HasFactory;

    // 1. Definir la tabla explícitamente
    protected $table = 'VTA_VENDEDOR';
    protected $primaryKey = 'COD_VENDEDOR';

    protected $fillable = [
        'COD_EMPRESA',
        'COD_VENDEDOR',
        'DES_VENDEDOR',
        'COD_AUXILIAR',
        'IND_BAJA',
        'FECHA_INGRESO',
        'FECHA_BAJA',
    ];

     public $timestamps = false; // Si tu tabla no tiene created_at y updated_at
}
