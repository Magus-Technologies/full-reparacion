<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    
    protected $table = 'proveedores';
    protected $primaryKey = 'proveedor_id';
    protected $fillable = [
        'ruc',
        'razon_social',
        'direccion',
        'telefono',
        'email',
        'id_empresa',
        'departamento',
        'provincia',
        'distrito',
        'ubigeo',
        'estado'
    ];

    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_proveedor', 'proveedor_id');
    }
}
