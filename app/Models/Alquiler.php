<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alquiler extends Model
{
    use HasFactory;

protected $table = 'alquileres';

    protected $fillable = ['cliente_id', 'vestido_id', 'fecha_inicio', 'fecha_fin', 'costo_total', 'estado'];

    public function vestido()
    {
        return $this->belongsTo(Vestido::class);
    }
    
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    

    public function devolucion()
    {
        return $this->hasOne(Devolucion::class);
    }
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];
}
