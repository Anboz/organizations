<?php

namespace App\Modules\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrmClientStoreRequest extends FormRequest
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
            'api_token' => 'required',
            'clientId' => 'required|integer',
            'surname' => 'required|string',
            'name' => 'required|string',
            'patronymic'=>'required|string',
            'clientRegistrationDate'=> 'required',
            ];
    }
}
