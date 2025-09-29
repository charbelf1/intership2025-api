<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'phone' => 'nullable|string|max:25',
            'password' => 'required|min:8|confirmed',
            'role' => 'in:customer,barber'
        ];
    }
}
