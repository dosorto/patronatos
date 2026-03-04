<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDirectivaRequest extends FormRequest
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
            'persona_id' => [
                'required',
                'exists:personas,id',
                function ($attribute, $value, $fail) {
                    $miembro = \App\Models\Miembros::where('persona_id', $value)->first();
                    if ($miembro) {
                        $existeEnDirectiva = \App\Models\Directiva::where('miembro_id', $miembro->id)->exists();
                        if ($existeEnDirectiva) {
                            $fail('Esta persona ya posee un cargo en la directiva activa.');
                        }
                    }
                },
            ],
            'cargo' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('directivas', 'cargo')->where(function ($query) {
                    return $query->where('organization_id', session('tenant_organization_id'));
                })
            ],
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
            'persona_id.required' => 'Debe seleccionar una persona para asignar el cargo.',
            'persona_id.exists' => 'La persona seleccionada no es válida.',
            'cargo.unique' => 'Ya existe una persona registrada con el cargo de "' . $this->cargo . '" en esta organización.',
        ];
    }
}
