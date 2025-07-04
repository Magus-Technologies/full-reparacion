<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerieProducto extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $table = 'series_productos';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_producto',
        'codigo_unico',
        'estado',
        'fecha_ingreso'
    ];

    protected $casts = [
        'fecha_ingreso' => 'datetime'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
