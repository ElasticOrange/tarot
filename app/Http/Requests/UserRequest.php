<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = \Auth::user();

        if ( ! $user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->email == $this->route('email')) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|min:2',
            'email' => 'required|email',
            'type' => 'integer|min:1|max:3',
            'active' => 'boolean',
            'password' => 'string|min:6'
        ];
    }
}
