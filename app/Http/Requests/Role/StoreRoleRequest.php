<?php

namespace App\Http\Requests\Role;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
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
            'name' => [
                'required', 
                'string', 
                'min:4', 
                'max:40', // Recomendado para evitar ataques de strings largos,
                // Regex para permitir solo letras, espacios y guiones (evita inyecciones de código)
                'regex:/^[a-zA-ZÀ-ÿ\s\-]+$/u',
                Rule::unique('roles', 'name'),
            ],
            'description' => [
                'required',
                'string',
                'min:5',
            ],
            'icon' => [
                'required',
                'string',
                'min:3',
            ],
            'permission_ids' => [
                'required', 'array', 'min:1',
            ],
            'permission_ids.*' => [
                Rule::exists('permissions', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
    
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.string'   => 'El nombre del rol debe ser un texto válido.',
            'name.min'      => 'El nombre del rol es demasiado corto (mínimo :min caracteres).',
            'name.max'      => 'El nombre del rol no puede exceder los :max caracteres.',
            'name.regex'    => 'El nombre del rol solo puede contener letras y espacios.',
            'name.unique'   => 'Ya existe un rol con este nombre, prueba con uno diferente.',

            'description.required' => 'La descripción es obligatoria para ayudar a identificar el propósito del rol.',
            'description.string'   => 'La descripción debe ser un texto válido.',
            'description.min'      => 'La descripción debe tener al menos :min caracteres.',
            'description.max'      => 'La descripción es demasiado larga (máximo :max caracteres).',

            'icon.required' => 'Debes seleccionar un icono representativo para el rol.',
            'icon.string'   => 'El formato del icono no es válido.',
            'icon.min'      => 'El nombre del icono es demasiado corto.',

            'permission_ids.required' => 'Debe seleccionar al menos un permiso para este rol.',
            'permission_ids.array'    => 'El formato de los permisos no es válido.',
            'permission_ids.min'      => 'El rol debe tener asignado al menos :min permiso.',
            'permission_ids.*.exists' => 'Uno de los permisos seleccionados no es válido o ha sido eliminado.',
        ];
    }
}
