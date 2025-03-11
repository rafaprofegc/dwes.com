<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticuloRequest extends FormRequest
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
        return [
            'referencia'    => ['required', 'string', 'max:15'],
            'descripcion'   => 'required|string|max:50',
            'pvp'           => 'required|numeric|min:0',
            'dto_venta'     => 'nullable|numeric|min:0|max:100',
            'und_vendidas'  => 'nullable|numeric|min:0',
            'und_disponibles' => 'nullable|numeric|min:0',
            //'fecha_disponible' => ['nullable', 'date', 'date_format:Y-m-d', Rule::date
            'fecha_disponible' => ['nullable', 'date', Rule::date('Y-m-d')->afterOrEqual(today())],
            'categoria'     => 'required|string|exists:categoria,id_categoria',
            'tipo_iva'      => ['nullable', 'string', Rule::in(['N','R','SR'])]
        ];
    }

    public function messages(): array {
        return [ 
            'referencia.required'    => "La referencia es un dato obligatorio",
            'referencia.string'      => "La referencia es una cadena de caracteres",
            'referencia.unique'      => "La referencia ya existe en la tabla artículo",
            'referencia.max'         => "La referencia como máximo tiene 15 caracteres",
            'descripcion'            => "La descripción tiene que ser como máximo 50 caracteres",
            'pvp.min'                => "El PVP no puede ser negativo",
            'pvp.numeric'            => "El PVP tiene que ser numérico",
            'pvp.required'           => "El PVP es obligatorio",
            'dto_venta'              => "El descuento es un número entre 0 y 100",
            'und_vendidas'           => "Las unds vendidas es un número mayor que 0",
            'und_disponibles'        => "Las unds disponibles es un número mayor que 0",
            'fecha_disponible'       => "La fecha disponible tiene que estar en el futuro",
            'categoria'              => "La categoria es obligatorio y tiene que existir",
            'tipo_iva'               => "El tipo de iva es N, R o SR"
        ];
    }
}
