<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'name' => 'required|string|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'DNI' => 'nullable|unique:users,DNI',
            'personal_name' => 'nullable|string',
            'cargo' => 'nullable|string',
            'corporative_email' => 'nullable|email',
        ];
    }

    public function messages(): array
    {
        return [
            'DNI.unique' => 'Ya se ha registrado un usuario con este DNI.',
            'email.unique' => 'Ya se ha registrado un usuario con este email.',
        ];
    }
}