<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDirectivaRequest extends FormRequest
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
            'miembro_id' => 'required|exists:miembros,id|unique:directivas,miembro_id,' . $this->route('directiva')->id,
            'organizacion_id' => 'required|exists:organizacion,id_organizacion',
            'cargo' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'miembro_id.unique' => 'El miembro seleccionado ya posee un cargo en la directiva activa. Un miembro no puede tener dos cargos a la vez.',
        ];
    }
}
