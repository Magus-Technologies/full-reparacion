<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoUpdateRequest extends FormRequest
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
        $productoId = $this->route('id') ?? $this->input('cod');
        
        return [
            'nombre' => 'required|string|max:255',
            'codigo' => [
                'required',
                'string',
                'max:20',
                Rule::unique('productos', 'codigo')->ignore($productoId, 'id_producto')
            ],
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
            // DESPUÉS de la línea: 'precio2' => 'nullable|numeric|min:0',
            // AGREGAR:
            'precio_menor' => 'nullable|numeric|min:0',
            'precio_mayor' => 'nullable|numeric|min:0',
            'afecto' => 'nullable|in:0,1',
            'usar_barra' => 'boolean',
            'cod_barra_manual' => 'nullable|string|max:100',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'usar_multiprecio' => 'boolean',
        ];
    }
}
