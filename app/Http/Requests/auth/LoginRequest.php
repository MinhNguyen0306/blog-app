<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'exists:users,email', 'email'],
            'password' => ['required', 'min: 6']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.exists' => 'Email is not registed',
            'email.email' => 'Email format is not valid',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 3 chars'
        ];
    }
}
