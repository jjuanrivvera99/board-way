<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest
{

    /**
     * Manejar un intento de validación fallido.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        $errors = (new ValidationException($validator))->errors();
        $errorsMapping = Arr::flatten($errors, 1);

        throw new HttpResponseException(
            response()->json(['errors' => $errorsMapping], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
