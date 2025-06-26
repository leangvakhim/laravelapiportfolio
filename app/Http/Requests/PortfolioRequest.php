<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PortfolioRequest extends FormRequest
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
            "p_img" => "nullable|integer|exists:tbimage,image_id",
            "p_title" => "nullable|string|max:255",
            "p_category" => "nullable|string|max:255",
            "p_detail" => "nullable|string",
            "p_order" => "nullable|integer",
            "display" => "nullable|integer",
        ];
    }
}
