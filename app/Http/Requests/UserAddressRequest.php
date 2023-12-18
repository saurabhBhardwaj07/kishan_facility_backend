<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use App\Traits\FailedRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
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
                'name' => 'required|string|min:3|max:255',
                'address' => 'required|string|max:255',
                'country' => 'required|string|min:3|max:255',
                'state' => 'required|string|min:3|max:255',
                'city' => 'required|string|min:3|max:255',
                'pincode' => 'required|numeric|regex:/^[0-9]{4,7}$/|digits:6',
                'is_default' => 'nullable|in:1,0',
            ];
        }

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = [
                'name' => 'required|string|min:3|max:255',
                'address' => 'required|string|max:255',
                'country' => 'required|string|min:3|max:255',
                'state' => 'required|string|min:3|max:255',
                'city' => 'required|string|min:3|max:255',
                'pincode' => 'required|numeric|regex:/^[0-9]{4,7}$/|digits:6',
                'is_default' => 'nullable|in:1,0',
            ];
        }

        return $rules;
    }
}
