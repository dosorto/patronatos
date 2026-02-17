<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaisRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Permitimos la actualización
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $paisId = $this->route('pais'); // Obtenemos el ID del país desde la ruta

        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pais', 'nombre')->ignore($paisId)->whereNull('deleted_at'),
            ],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del país',
        ];
    }
}
