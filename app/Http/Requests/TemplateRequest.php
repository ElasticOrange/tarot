<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class TemplateRequest extends Request
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
            'name' => 'required|string',
            'category' => 'required|string|min:3|in:question,email',
            'type' => 'required|string|min:3',
            'content' => 'requires|string|min:3'
            'active' => 'boolean',
        ];
    }
}
