<?php

namespace App\Http\Requests;

use App\Traits\FailedRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
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
                'title' => 'required|string|min:10|max:255',
                'link' => 'required|url',
                'status' => 'required|in:1,0',
            ];
        }

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'title' => 'required|string|min:10|max:255',
                'link' => 'required|url',
                'status' => 'nullable|in:1,0',
            ];
        }

        return $rules;
    }
}
