<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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
        if ($this->route('id')) {
            return [
                'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240'
            ];
        }
        return [
            'img' => 'required|array',
            'img.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240'
        ];
    }
}
