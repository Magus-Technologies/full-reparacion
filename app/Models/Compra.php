<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Compra extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $table = 'compras';
    protected $primaryKey = 'id_compra';
    
    protected $fillable = [
        'id_tido',
        'id_tipo_pago',
        'id_proveedor',
        'fecha_emision',
        'fecha_vencimiento',
        'dias_pagos',
        'direccion',
        'serie',
        'numero',
        'total',
        'total_soles',
        'estado_recepcion',
        'id_empresa',
        'moneda',
        'tasa_cambio',
        'sucursal',
        'id_usuario',
        'observaciones',
        'tiene_igv',
        'igv'
    ];

    protected $casts = [
        'total_soles' => 'decimal:2',
        'tasa_cambio' => 'decimal:4',
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date'
    ];

    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'proveedor_id');
    }

    public function productosCompras()
    {
        return $this->hasMany(ProductoCompra::class, 'id_compra', 'id_compra');
    }

    public function diasCompras()
    {
        return $this->hasMany(DiaCompra::class, 'id_compra', 'id_compra');
    }

    // NUEVO - Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_usuario', 'id');
    }

}
