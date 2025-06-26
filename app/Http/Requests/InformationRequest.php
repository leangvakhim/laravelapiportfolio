<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformationRequest extends FormRequest
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
            "i_img" => "nullable|integer|exists:tbimage,image_id",
            "i_title" => "nullable|string|max:255",
            "i_detail" => "nullable|string",
            "i_type" => "nullable|integer",
            "display" => "nullable|integer"
        ];
    }
}
