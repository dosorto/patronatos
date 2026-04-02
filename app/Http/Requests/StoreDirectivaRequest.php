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
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'cargos' => 'required|array',
            'cargos.*.cargo_name' => 'required|string',
            'cargos.*.persona_id' => 'nullable|exists:personas,id',
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
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required' => 'La fecha de finalización es obligatoria.',
            'fecha_fin.after_or_equal' => 'La fecha de finalización debe ser posterior o igual a la fecha de inicio.',
            'cargos.required' => 'Debe asignar al menos un cargo.',
            'cargos.*.persona_id.exists' => 'Una de las personas seleccionadas no es válida.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $cargos = $this->input('cargos', []);
            $opcionales = ['Prosecretario', 'Vocal 4', 'Vocal 5'];

            if (is_array($cargos)) {
                foreach ($cargos as $index => $cargo) {
                    $nombreCargo = $cargo['cargo_name'] ?? '';
                    $personaId = $cargo['persona_id'] ?? null;

                    if (!in_array($nombreCargo, $opcionales) && empty($personaId)) {
                        $validator->errors()->add(
                            "cargos.{$index}.persona_id",
                            "El cargo de {$nombreCargo} es obligatorio."
                        );
                    }
                }
            }
        });
    }
}
