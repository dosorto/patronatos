<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaisRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Cambia a true para que los usuarios autorizados puedan hacer la petición
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
            'nombre' => 'required|string|max:255|unique:pais,nombre',
            'iso' => 'required|string|max:3|unique:pais,iso'
        ];
    }

    // Opcional: mensajes personalizados
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del país es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'nombre.unique' => 'Ya existe un país con ese nombre.',
            'iso.required' => 'El código ISO es obligatorio.',
            'iso.string' => 'El código ISO debe ser texto.',
            'iso.max' => 'El código ISO no puede tener más de 5 caracteres.',
            'iso.unique' => 'Ya existe un país con ese código ISO.',
        ];
    }
}
