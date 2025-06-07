<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Producto extends Model
{

    use HasFactory;

    public $timestamps = false;

    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'cod_barra',
        'nombre',
        'precio',
        'costo',
        'cantidad',
        'iscbp',
        'id_empresa',
        'sucursal',
        'ultima_salida',
        'codsunat',
        'usar_barra',
        'usar_multiprecio',
        'precio_mayor',
        'precio_menor',
        'razon_social',
        'ruc',
        'estado',
        'almacen',
        'precio2',
        'precio3',
        'precio4',
        'precio_unidad',
        'codigo',
        'imagen',
        'detalle',
        'categoria',
        'descripcion',
        'unidad'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'costo' => 'decimal:2',
        'precio_mayor' => 'decimal:2',
        'precio_menor' => 'decimal:2',
        'precio2' => 'decimal:2',
        'precio3' => 'decimal:2',
        'precio4' => 'decimal:2',
        'precio_unidad' => 'decimal:2',
        'ultima_salida' => 'date',
        'usar_barra' => 'boolean',
        'usar_multiprecio' => 'boolean',
        'iscbp' => 'boolean',
        'estado' => 'boolean'
    ];

    // Relaciones
    public function precios()
    {
        return $this->hasMany(ProductoPrecio::class, 'id_producto', 'id_producto');
    }

    public function categoriaRelacion()
    {
        return $this->belongsTo(\App\Models\Categoria::class, 'categoria');
    }

    public function obtenerUnidad()
    {
        return DB::table('unidades_medida')
            ->where('id', $this->unidad) // 'unidad' es la columna FK en productos
            ->select('id', 'nombre')
            ->first();
    }

    public function unidadRelacion()
    {
        return $this->belongsTo(\App\Models\UnidadMedida::class, 'unidad');
    }


    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', '1');
    }

    public function scopeAlmacenPrincipal($query)
    {
        return $query->where('almacen', '1');
    }

    // Accessors
    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return asset('storage/productos/' . $this->imagen);
        }
        return null;
    }

    public function getPrecioFormateadoAttribute()
    {
        return 'S/ ' . number_format($this->precio, 2);
    }

    public function getCostoFormateadoAttribute()
    {
        return 'S/ ' . number_format($this->costo, 2);
    }
}
