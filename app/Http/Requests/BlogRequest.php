<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            "b_img" => "nullable|integer|exists:tbimage,image_id",
            "b_title" => "nullable|string|max:255",
            "b_subtitle" => "nullable|string",
            "b_detail" => "nullable|string",
            "b_date" => "nullable|date",
            "b_order" => "nullable|integer",
            "display" => "nullable|integer",
        ];
    }
}
