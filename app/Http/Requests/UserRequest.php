<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use App\Traits\FailedRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        return [
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'. Auth::id(),
            'phone' => 'nullable|digits:10|numeric|unique:users,phone,' . Auth::id(),
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
