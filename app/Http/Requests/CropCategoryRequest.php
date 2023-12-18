<?php

namespace App\Http\Requests;

use App\Traits\FailedRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CropCategoryRequest extends FormRequest
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
                'name' => 'required|string|min:5|max:255|unique:crop_categories,name',
                'description' => 'required|string|min:10|max:10000',
                'status' => 'required|in:1,0',
            ];
        }

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'name' => 'required|string|min:5|max:255|unique:crop_categories,name,' . $this->crop_category->id,
                'description' => 'required|string|min:10|max:10000',
                'status' => 'nullable|in:1,0',
            ];
        }

        return $rules;
    }
}
