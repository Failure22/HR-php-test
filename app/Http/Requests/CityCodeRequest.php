<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityCodeRequest extends FormRequest
{
    use ValidateSlugs;

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
     * @return array
     */
    public function slugs()
    {
        return ['cityCode'];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cityCode' => 'exists:cities,code'
        ];
    }
}
