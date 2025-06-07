<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoPrecio extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'producto_precios';

    protected $fillable = [
        'id_producto',
        'nombre',
        'precio'
    ];

    protected $casts = [
        'precio' => 'decimal:2'
    ];

    // Relaciones
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    // Accessors
    public function getPrecioFormateadoAttribute()
    {
        return 'S/ ' . number_format($this->precio, 2);
    }
}
