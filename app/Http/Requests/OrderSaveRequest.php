<?php

namespace App\Http\Requests;

use App\Rules\OrderStatusRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderSaveRequest extends FormRequest
{
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
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'exists:orders,id'],
            'client_email' => ['required', 'email'],
            'partner_id' => ['required', 'exists:partners,id'],
            'status' => ['required', new OrderStatusRule()]
        ];
    }
}
