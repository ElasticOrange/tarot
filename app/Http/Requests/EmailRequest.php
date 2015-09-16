<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EmailRequest extends Request
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
            'email' => 'required|string|min:3|email',
            'sender' => 'required|string|min:3',
            'subject' => 'required|string|min:3',
            'content' => 'required|string|min:20'
        ];
    }
}
