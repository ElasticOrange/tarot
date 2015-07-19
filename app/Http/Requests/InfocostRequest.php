<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class InfocostRequest extends Request
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
            'country' => 'required|string',
            'telephone' => 'required|string|min:3',
            'infocost' => 'required|string|min:3',
            'active' => 'boolean',
            'default' => 'boolean'
        ];
    }
}
