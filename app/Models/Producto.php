<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;



class Producto extends Model
{
    use HasFactory;

    public function compras(){
        return $this->belongsToMany(Compra::class)->withTimestamps()->withPivot('cantidad','precio_compra','precio_venta');
    }

    public function interes()
{
    return $this->hasOne(Interes::class);
}

public function calcularInteres()
    {
        // Supongamos que tienes una columna 'precio_compra' en tu tabla productos
        // Y calculas el interés como 25% del precio de compra
        return $this->precio_compra * 0.25;
    }


    protected $fillable = ['codigo','nombre','precio_compra','descripcion','fecha_vencimiento','img_path','numero_celular','nombre_del_producto','cedula','monto_interes','monto_interes_updated_at_anterior','estado_anterior','monto_interes_updated_at','estado'];

    public function hableUploadImage($image){
        $file = $image;
        $name = time() . $file->getClientOriginalName();
        //$file->move(public_path().'/img/productos/',$name);
        Storage::putFileAs('public/productos/',$file,$name,'public');

        return $name;
    }

    public static function boot()
    {
        parent::boot();

        // Antes de actualizar el producto
        static::updating(function ($producto) {
            // Guarda el estado actual en el campo estado_anterior antes de que cambie
            $producto->estado_anterior = $producto->getOriginal('estado');
 });
}
}



