<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoCompra extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $table = 'productos_compras';
    protected $primaryKey = 'id_producto_venta';
    
    protected $fillable = [
        'id_producto',
        'id_compra',
        'cantidad',
        'precio',
        'costo',
        'subtotal',
        'precio_soles',
        'moneda_original',
        'igv_producto'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'precio_soles' => 'decimal:2',
        'igv_producto' => 'decimal:2'

    ];

    // Relaciones
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'id_compra', 'id_compra');
    }
}
