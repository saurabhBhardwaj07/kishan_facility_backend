<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait FailedRequestValidation
{
    /**
     * Function For diplaying errors in the API's
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'data' => null,
                'message' => $validator->errors()->first()
            ],
            422
        ));
    }
}
