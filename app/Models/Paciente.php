<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
   protected $table = 'ADM_PACIENTE';
    protected $primaryKey = 'COD_PACIENTE'; // Basado en tu imagen
    public $incrementing = false;
    public $timestamps = false;
}
