<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFacturaRequest extends FormRequest
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
            "iva_normal" => "nullable|decimal:1,2",
            "iva_reduc" => "nullable|decimal:1,2",
            "iva_super" => "nullable|decimal:1,2",
        ];
    }
    public function messages(): array
    {
        return [
            "iva_normal.decimal" => "El IVA normal debe ser un número con dos decimales",
            "iva_reduc.decimal" => "El IVA reducido debe ser un número con dos decimales",
            "iva_super.decimal" => "El IVA superreducido debe ser un número con dos decimales",
        ];
    }
}
