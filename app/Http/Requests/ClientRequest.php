<?php

namespace App\Http\Requests;
use Illuminate\Http\Request as HttpRequest;
use App\Http\Requests\Request;
use App\Client as Client;

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
    public function rules(HttpRequest $request)
    {
        $rules = [
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

        $emailaddress = $this->input('email');
        $site = $this->route('sites');
        $client = $this->route('clients');

        if (empty($emailaddress) or (!is_string($emailaddress)) or (!$site)) {
            return $rules;
        }
        if (!$client or ($client->emailaddress != $emailaddress)) {
            $client = Client::byEmailAndSite($emailaddress, $site->id);
            if ($client) {
                $rules['email'] = 'required|string|email|unique:email_list_subscribers,emailaddress';
            }
        }

        return $rules;
    }
}
