<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $producto = $this->route('producto');
        return [
            'codigo'=> 'required|unique:productos,codigo,'.$producto->id.'|max:45',
            'nombre'=> 'required',
            'precio_compra' => 'required',
            'descripcion'=> 'nullable|max:255',
            'fecha_vencimiento'=> 'nullable|date',
            'image_path'=> 'nullable|image|mimes:png,jpg,jpeg,|max:2048',
            'numero_celular' => 'nullable',
            'cedula'=> 'nullable',
            'nombre_del_producto' => 'required',
            'monto_interes' => 'nullable'

        ];
    }
    public function messages(){
        return[
            'codigo.required'=> 'Se necesita un campo Boleta',
            'nombre.required'=> 'Se necesita un nombre',
        ];
    }
}
