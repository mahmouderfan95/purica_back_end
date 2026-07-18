<?php

namespace App\Http\Requests\Front\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'name'     => ['sometimes', 'string', 'max:255'],
            'email'    => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore(auth('api')->id())],
            'phone'    => ['sometimes', 'string', 'regex:/^01[0-9]{9}$/',
                Rule::unique('users', 'phone')->ignore(auth('api')->id())],
            'address'  => ['sometimes', 'string'],
            'password' => ['sometimes', 'string', 'min:8'],
        ];
    }
}
