<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SiteRequest extends Request
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
            'name' => 'required|min:3',
            'url' => 'required|url',
            'active' => 'boolean',
            'sender' => 'string|min:3',
            'email' => 'email',
            'subject' => 'string|min:3',
            'signature' => 'string',
            'unsubscribe' => 'string',
            'emailbox_id' => 'integer|min:1'
        ];
    }
}
