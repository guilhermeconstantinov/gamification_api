<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "name" => "required|string",
            "email" => "string|email|unique:users,email",
            "phone" => "required|string|unique:users,phone",
            "password" => "required|string",
            "birthdate" => "required|date",
            "gender" => "required|in:M,F,O",
            "high_school" => "required|boolean",
            "school_year" => "integer|nullable",
            "undergraduate_program" => "required|boolean"
        ];
    }
}
