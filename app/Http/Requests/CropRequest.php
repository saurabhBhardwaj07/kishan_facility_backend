<?php

namespace App\Http\Requests;

use App\Traits\FailedRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CropRequest extends FormRequest
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
                'name' => 'required|string|min:4|max:255|unique:crops,name',
                'crop_category_id' => 'required|exists:crop_categories,id',
                'introduction' => 'required|string|min:10|max:10000',
                'climate' => 'required|string|min:10|max:10000',
                'soil' => 'required|string|min:10|max:10000',
                'season' => 'required|string|min:10|max:10000',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status' => 'required|in:1,0',
            ];
        }

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'name' => 'required|string|min:4|max:255|unique:crops,name,' . $this->crop->id,
                'crop_category_id' => 'required|exists:crop_categories,id',
                'introduction' => 'required|string|min:10|max:10000',
                'climate' => 'required|string|min:10|max:10000',
                'soil' => 'required|string|min:10|max:10000',
                'season' => 'required|string|min:10|max:10000',
                'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status' => 'nullable|in:1,0',
            ];
        }

        return $rules;
    }
}
