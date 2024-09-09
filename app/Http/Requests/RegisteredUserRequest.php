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
            'phone_number' => 'required|phone:' . $this->input('country'), // Validate phone based on selected country
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
            'phone' => 'The :attribute field contains an invalid number.',
        ];
    }

    /**
     * Format phone number after validation.
     *
     * @param string|null $key
     * @param mixed|null $default
     * @return array
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);

        // Format phone number to E.164 format if phone_number exists
        if (isset($validated['phone_number']) && isset($validated['country'])) {
            $validated['phone_number'] = phone($validated['phone_number'], $validated['country'])->formatE164();
        }

        return $validated;
    }
}

