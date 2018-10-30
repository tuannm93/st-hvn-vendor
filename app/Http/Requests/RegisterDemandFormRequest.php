<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidationFactory;

class RegisterDemandFormRequest extends FormRequest
{
    /**
     * RegisterDemandFormRequest constructor.
     *
     * @param ValidationFactory $validationFactory
     */
    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend(
            'emailfax',
            function ($attribute, $value, $parameters) {
                return 'emailfax' === $value;
            },
            'Sorry, it failed foo validation!'
        );
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'fields' => 'numericarray|emailfax',
        ];
    }
}
