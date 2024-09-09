<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisteredUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'country' => 'required|string|max:255', // Country validation
            'phone_number' => 'required|string|min:10|max:15', // Phone number validation
        ];
    }

    /**
     * Custom error messages for validation.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'country.required' => 'Please select your country.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.min' => 'The phone number must be at least 10 digits long.',
            'phone_number.max' => 'The phone number must not exceed 15 digits.',
        ];
    }
}
