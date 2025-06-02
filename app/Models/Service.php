<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Service extends Model
{
    protected $table = 'service_list';
    public $timestamps = false;

    protected $fillable = [
        'service', 'description', 'cost'
    ];

    // En Laravel 12, se recomienda usar casts en lugar de $dates
    protected $casts = [
        'date_created' => 'datetime',
        'date_updated' => 'datetime',
    ];

    // Método para asegurar que date_created siempre sea un objeto Carbon
    protected function dateCreated(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value) : null,
        );
    }

    // Método para asegurar que date_updated siempre sea un objeto Carbon
    protected function dateUpdated(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value) : null,
        );
    }

    public function repairs()
    {
        return $this->belongsToMany(Repair::class, 'repair_services');
    }
}