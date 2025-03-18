<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            // 'username' => 'required|unique:users',
            'email' => 'email|required|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ];
    }

    public function attributes()
    {
        return [
            // 'username.required' => 'username is required',
            'email.required' => 'email is required',
            'email.email' => 'please use proper email format',
            'email.unique' => 'this email is already registered',
            'password.required' => 'password is required',
            'password_confirmation.required' => 'confirm password is required',
            'password_confirmation.same' => 'confirm password does not match',
        ];
    }
}
