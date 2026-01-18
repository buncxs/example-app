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
            'permission_ids' => [
                'required', 'array', 'min:1',
            ],
            'permission_ids.*' => [
                Rule::exists('permissions', 'uuid'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.min' => 'El nombre del rol es demasiado corto (mínimo 4 caracteres).',
            'name.regex' => 'El nombre del rol solo puede contener letras y espacios (evita números o símbolos).',
            'name.unique' => 'Ya existe un rol con este nombre, prueba con uno diferente.',
            'permission_ids.required' => 'Debe seleccionar al menos un permiso para este rol.',
            'permission_ids.min' => 'El rol debe de tener asignado al menos :min permiso.',
            'permission_ids.*.exists' => 'Uno de los permisos seleccionados no es valido o ha sido eliminado.',
        ];
    }
}
