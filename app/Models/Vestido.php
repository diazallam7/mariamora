<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vestido extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'precio_alquiler',
        'precio_venta',
        'estado',
        'talla',
        'color',
        'categoria',
    ];


    public function alquileres()
{
    return $this->hasMany(Alquiler::class);
}

    
}
