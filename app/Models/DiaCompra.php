<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaCompra extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $table = 'dias_compras';
    protected $primaryKey = 'dias_compra_id';
    
    protected $fillable = [
        'id_compra',
        'monto',
        'fecha',
        'estado'
    ];

    protected $casts = [
        'monto' => 'decimal:3',
        'fecha' => 'date'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'id_compra', 'id_compra');
    }
}
