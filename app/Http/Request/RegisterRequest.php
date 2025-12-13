<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'studentId' => 'required|string|unique:users,student_id_number',
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|in:Student Assistant,Office Head,HR',
            'office' => 'nullable|string|required_if:role,Student Assistant,Office Head',
            'password' => 'required|string|min:6',
            'confirmPassword' => 'required|string|same:password'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'studentId.required' => 'Student ID is required.',
            'studentId.unique' => 'This Student ID is already registered.',
            'fullName.required' => 'Full Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'role.required' => 'Role is required.',
            'role.in' => 'Please select a valid role.',
            'office.required_if' => 'Office is required for this role.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'confirmPassword.required' => 'Password confirmation is required.',
            'confirmPassword.same' => 'Passwords do not match.'
        ];
    }
}
