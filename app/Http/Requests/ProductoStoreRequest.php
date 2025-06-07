<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
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
        return [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:20|unique:productos,codigo',
            'precio' => 'required|numeric|min:0',
            'costo' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
            'categoria' => 'nullable|exists:categorias,id',
            'unidad' => 'nullable|exists:unidad_medidas,id',
            'detalle' => 'nullable|string',
            'codsunat' => 'nullable|string|max:20',
            'iscbp' => 'boolean',
            'precio1' => 'nullable|numeric|min:0',
            'precio2' => 'nullable|numeric|min:0',
            'precio_menor' => 'nullable|numeric|min:0',  // Agregar esta línea
            'precio_mayor' => 'nullable|numeric|min:0',  // Agregar esta línea
            'afecto' => 'nullable|in:0,1',
            'usar_barra' => 'boolean',
            'cod_barra_manual' => 'nullable|string|max:100',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'usar_multiprecio' => 'boolean',
            'precios' => 'nullable|array',
            'precios.*.nombre' => 'required_with:precios|string|max:255',
            'precios.*.precio' => 'required_with:precios|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del producto es obligatorio',
            'codigo.required' => 'El código del producto es obligatorio',
            'codigo.unique' => 'Este código ya existe',
            'precio.required' => 'El precio es obligatorio',
            'precio.min' => 'El precio debe ser mayor a 0',
            'costo.required' => 'El costo es obligatorio',
            'cantidad.required' => 'La cantidad es obligatoria',
            'imagen.image' => 'El archivo debe ser una imagen',
            'imagen.max' => 'La imagen no debe superar los 2MB'
        ];
    }
}
