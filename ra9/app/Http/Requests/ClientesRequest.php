<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientesRequest extends FormRequest
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
            "nif"           => "required|string|regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/",
            "nombre"        => "required|string",
            "apellidos"     => "required|string",
            "clave"         => "required|string",
            "iban"          => "required|string|regex:/^[A-Z]{2}\d{2}([ \-]\d{4}){2}[ \-]\d{2}[ \-]\d{10}$/",
            "telefono"      => "nullable|string|regex:/^([67]\d{2} \d{3} \d{3})$/",
            "email"         => "required|email",
            "ventas"        => "nullable|numeric|min:0"
        ];
    }

    public function messages(): array
    {
        return [
            "nif.required"          => "El NIF es un dato obligatorio",
            "nif.string"            => "El NIF debe ser una cadena de caracteres",
            "nif.regex"             => "El NIF debe estar compuesto por ocho digitos seguidos de una letra válida en mayúscula.",
            "nombre.required"       => "El nombre es un dato obligatorio",
            "nombre.string"         => "El nombre debe ser una cadena de caracteres",
            "apellidos.required"    => "Los apellidos son un dato obligatorio",
            "apellidos.string"      => "Los apellidos deben ser una cadena de caracteres",
            "clave.required"        => "La clave es un dato obligatorio",
            "clave.string"          => "La clave debe ser una cadena de caracteres",
            "iban.required"         => "El IBAN es un dato obligatorio",
            "iban.string"           => "El IBAN debe ser una cadena de caracteres",
            "iban.regex"            => "El IBAN debe tener un formato tal que: 'ES00 0000 0000 00 0000000000'",
            "telefono.string"       => "El teléfono debe ser una cadena de caracteres numéricos",
            "telefono.regex"        => "El teléfono debe empezar por 6 ó 7 y tener un formato tal que: '600 000 000'",
            "email.required"        => "El correo electrónico es un dato obligatorio",
            "email.email"           => "El correo electrónico debe tener un formato válido",
            "ventas.numeric"        => "Las ventas deben ser una cantidad numérica",
            "ventas.min"            => "Las ventas no pueden ser menores a 0"
        ];
    }
}
