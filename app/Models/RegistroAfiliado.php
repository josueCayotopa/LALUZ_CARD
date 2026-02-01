<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroAfiliado extends Model
{
  use HasFactory;

    // 1. Definir la tabla explícitamente
    protected $table = 'Registros_Afiliados_LUZCARD';

    // 2. Definir la Primary Key (Laravel asume 'id' por defecto)
    protected $primaryKey = 'ID_Registro';

    // 3. Desactivar los timestamps automáticos de Laravel (created_at, updated_at)
    //    ya que tu tabla usa 'Fecha_Creacion' y 'Fecha_Registro'.
    public $timestamps = false;

    // 4. Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'Orientador',
        'Fecha_Registro',
        'Afiliado_Nombres',
        'Afiliado_DNI',
        'Afiliado_Telefono',
        'Afiliado_Direccion',
        'Afiliado_Email',
        'Apoderado_Parentesco',
        'Apoderado_Nombres',
        'Apoderado_DNI',
        'Apoderado_Telefono',
        'Apoderado_Direccion',
        'Apoderado_Email',
        'Tiene_Firma_Huella',
        'Contrato_adjunto',
        'Estado_Registro',
        'Fecha_Creacion'
    ];

    // 5. Casts para asegurar tipos de datos correctos
    protected $casts = [
        'Fecha_Registro' => 'date',
        'Fecha_Creacion' => 'datetime',
        'Tiene_Firma_Huella' => 'boolean', // Convierte el BIT de SQL Server a true/false
    ];

    /**
     * Boot function para manejar valores por defecto desde Laravel si es necesario.
     */
    protected static function boot()
    {
        parent::boot();

        // Al crear, asignamos la fecha de creación si no viene en la data
        static::creating(function ($model) {
            if (empty($model->Fecha_Creacion)) {
                $model->Fecha_Creacion = now();
            }
            if (empty($model->Fecha_Registro)) {
                $model->Fecha_Registro = now(); // O la fecha actual
            }
            // Estado por defecto si no se envía
            if (empty($model->Estado_Registro)) {
                $model->Estado_Registro = 'ACT';
            }
        });
    }
}
