<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMiembroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'direccion' => 'nullable|string|max:255',
            'estado'    => 'required|boolean',
            'nombre'    => 'required|string|max:255',
            'apellido'  => 'required|string|max:255',
            'dni'       => 'required|string|max:50',
            'fecha_nacimiento' => 'nullable|date',
            'sexo'      => 'nullable|in:M,F',
            'telefono'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'estado.required' => 'El estado es obligatorio.',
            'estado.boolean'  => 'El estado debe ser verdadero o falso.',
            'nombre.required' => 'El nombre de la persona es obligatorio.',
            'apellido.required' => 'El apellido de la persona es obligatorio.',
            'dni.required' => 'El DNI es obligatorio.',
            'email.email' => 'Ingresa un email válido.',
        ];
    }
}