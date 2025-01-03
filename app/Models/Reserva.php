<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'vestido_id', 'fecha_reserva', 'fecha_evento', 'estado'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function vestido()
    {
        return $this->belongsTo(Vestido::class);
    }

    protected $casts = [
        'fecha_reserva' => 'datetime',
        'fecha_evento' => 'datetime',
    ];
}
