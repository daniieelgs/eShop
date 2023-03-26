<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'img' => 'required|mimes:png,jpg,jpeg|max:2048',
            'description' => 'required',
            'category' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ];
    }
}
