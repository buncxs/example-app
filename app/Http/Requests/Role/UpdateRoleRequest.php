<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
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
        // Obtenemos el usuario de la ruta para el "unique ignore"
        $role = $this->route('role');

        return [
            'name' => [
                'required', 
                'string', 
                'min:4', 
                'max:50', // Recomendado para evitar ataques de strings largos
                Rule::unique('roles')->ignore($role->id),
            ],
        ];
    }
}
