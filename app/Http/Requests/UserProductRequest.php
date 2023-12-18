<?php

namespace App\Http\Requests;

use App\Traits\FailedRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class UserProductRequest extends FormRequest
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
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|numeric|between:1,500',
                'price' => 'required|numeric|between:0,9999999.99',
            ];
        }

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'quantity' => 'required|numeric|between:1,500',
                'price' => 'required|numeric|between:0,9999999.99',
            ];
        }

        return $rules;

    }
}
