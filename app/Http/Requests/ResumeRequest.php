<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResumeRequest extends FormRequest
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
            "r_title" => "nullable|string|max:255",
            "r_duration" => "nullable|string|max:100",
            "r_detail" => "nullable|string",
            "r_type" => "nullable|integer",
            "display" => "nullable|integer"
        ];
    }
}
