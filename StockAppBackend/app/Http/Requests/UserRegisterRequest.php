<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:4', 'max:70'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'numeric', 'exists:access_type,id_access_type'],
            'password' => ['required', 'min:5', 'confirmed']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.min' => 'The name must be at least 4 characters.',
            'name.max' => 'The name may not be greater than 70 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'role.required' => 'The role field is required.',
            'role.numeric' => 'The role must be a number.',
            'role.exists' => 'The selected role is invalid.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 5 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
