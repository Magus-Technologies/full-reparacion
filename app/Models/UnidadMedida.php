<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $table = 'unidades_medida'; // nombre exacto de la tabla

    protected $primaryKey = 'id'; // clave primaria

    public $timestamps = false; // desactivamos timestamps automáticos de Laravel

    protected $fillable = ['nombre', 'creado_el'];
}
