<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemittanceStoreRequest extends FormRequest
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
            'value' => 'required|numeric|min:1|max:9999999999',
            'payer_id' => 'required|numeric',
            'recipient_id' => 'required|numeric|different:payer_id',
            'do_at' => 'required|date|after:now',
        ];
    }
}
