<?php

namespace App\Http\Requests;

use App\Traits\FailedRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    use FailedRequestValidation;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        if ($this->method() == 'POST') {
            $rules = [
                'name' => 'required|string|min:3|max:255|unique:products,name',
                'product_category_id' => 'required|exists:product_categories,id',
                'quantity' => 'required|numeric|between:1,500',
                'size' => 'required|string|min:2|max:10',
                'price' => 'required|numeric|between:0,9999999.99',
                'discount' => 'required|numeric|between:1,100',
                'description' => 'required|string|min:10|max:10000',
                'composition' => 'required|string|min:10|max:10000',
                'uses' => 'required|string|min:10|max:10000',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status' => 'required|in:1,0',
            ];
        }

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'name' => 'required|string|min:3|max:255|unique:products,name,' . $this->products->id,
                'product_category_id' => 'required|exists:product_categories,id',
                'quantity' => 'required|numeric|between:1,500',
                'size' => 'required|string|min:2|max:10',
                'price' => 'required|numeric|between:0,9999999.99',
                'discount' => 'required|numeric|between:1,100',
                'description' => 'required|string|min:10|max:10000',
                'composition' => 'required|string|min:10|max:10000',
                'uses' => 'required|string|min:10|max:10000',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status' => 'nullable|in:1,0',
            ];
        }

        return $rules;
    }
}
