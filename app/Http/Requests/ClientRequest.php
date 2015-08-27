<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ClientRequest extends Request
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
            'email' => 'required|string|email',
            'firstName' => 'required|string|min:2',
            'lastName' => 'string',
            'partnerName' => 'string',
            'birthDate' => 'date',
            'gender' => 'required|string|min:3|in:Male,Female',
            'interest' => 'string',
            'country' => 'string',
            'ignore' => 'boolean',
            'problem' => 'boolean',
            'comment' => 'string'
        ];
    }
}
