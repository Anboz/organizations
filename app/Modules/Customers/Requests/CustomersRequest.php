<?php

namespace App\Modules\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomersRequest extends FormRequest
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
            'initials' => 'required|max:255',
            'operation_type' => 'required|max:255',
            'api_token' => 'required',
        ];
    }
}
