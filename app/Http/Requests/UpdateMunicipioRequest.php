<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMunicipioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $municipioId = $this->route('municipio');

        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('municipios', 'nombre')->ignore($municipioId),
            ],
            'departamento_id' => 'required|exists:departamentos,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'         => 'El nombre del municipio es obligatorio.',
            'nombre.string'           => 'El nombre debe ser texto.',
            'nombre.max'              => 'El nombre no puede tener más de 255 caracteres.',
            'nombre.unique'           => 'Ya existe un municipio con ese nombre.',
            'departamento_id.required' => 'El departamento es obligatorio.',
            'departamento_id.exists'   => 'El departamento seleccionado no existe.',
        ];
    }
}