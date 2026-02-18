<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $departamentoId = $this->route('departamento');

        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departamentos', 'nombre')->ignore($departamentoId),
            ],
            'pais_id' => 'required|exists:pais,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'  => 'El nombre del departamento es obligatorio.',
            'nombre.string'    => 'El nombre debe ser texto.',
            'nombre.max'       => 'El nombre no puede tener más de 255 caracteres.',
            'nombre.unique'    => 'Ya existe un departamento con ese nombre.',
            'pais_id.required' => 'El país es obligatorio.',
            'pais_id.exists'   => 'El país seleccionado no existe.',
        ];
    }
}