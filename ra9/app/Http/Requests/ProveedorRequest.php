<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProveedorRequest extends FormRequest
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
        $isPatch = $this->isMethod("patch");

        return [
            'nif' => [$isPatch ? 'nullable' : 'required', 'string', 'max:15'],
            'razon_social' => ['required', 'string', 'max:50'],
            'direccion' => ['required', 'string', 'max:100'],
            'cp' => ['required', 'string', 'max:5'],
            'poblacion' => ['required', 'string', 'max:40'],
            'provincia' => ['required', 'string', 'max:15'],
            'pais' => ['required', 'string', 'max:20'],
            'telefono' => ['required', 'string', 'max:20'],
            'contacto' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'string', 'email', 'max:100'],
        ];
    }

    public function messages()
    {
        return [
            'nif.required' => 'El NIF es obligatorio.',
            'nif.max' => 'El NIF no puede tener más de 15 caracteres.',
            'razon_social.required' => 'La razón social es obligatoria.',
            'razon_social.max' => 'La razón social no puede superar los 50 caracteres.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.max' => 'La dirección no puede superar los 100 caracteres.',
            'cp.required' => 'El código postal es obligatorio.',
            'cp.max' => 'El código postal no puede superar los 5 caracteres.',
            'poblacion.required' => 'La población es obligatoria.',
            'poblacion.max' => 'La población no puede superar los 40 caracteres.',
            'provincia.required' => 'La provincia es obligatoria.',
            'provincia.max' => 'La provincia no puede superar los 15 caracteres.',
            'pais.required' => 'El país es obligatorio.',
            'pais.max' => 'El país no puede superar los 20 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.max' => 'El teléfono no puede superar los 20 caracteres.',
            'contacto.max' => 'El contacto no puede superar los 100 caracteres.',
            'email.email' => 'El email debe ser una dirección de correo válida.',
            'email.max' => 'El email no puede superar los 100 caracteres.',
        ];
    }
}
