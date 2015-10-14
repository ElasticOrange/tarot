<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EmailboxRequest extends Request
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
            'name' => 'required|string|min:3',
            'smtpServer' => 'string|min:3',
            'smtpPort' => 'integer|min:0|max:65535',
            'smtpEncryption' => 'string|in:ssl,tls',
            'smtpUsername' => 'string',
            'smtpPassword' => 'string|min:3',
            'imapServer' => 'string|min:3',
            'imapPort' => 'integer|min:0|max:65535',
            'imapProtocol (imap/pop)' => 'string|min:3',
            'imapEncryption' => 'string|in:ssl,tls',
            'imapFolder' => 'string|min:3',
            'imapUsername' => 'string',
            'imapPassword' => 'string|min:3',
            'comment' => 'string',
        ];
    }
}
