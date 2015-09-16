<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmailRequest;
use App\Http\Controllers\Controller;
use App\Email as Email;


class EmailsController extends Controller
{
    public function lastEmails($site, $clientEmailAddress, $emailCount = 5) {
        $emails = Email::forEmailAddress($clientEmailAddress)->limit($emailCount)->latest()->with('attachments')->get();

        return $emails;
    }


    public function unrespondedQuestions($site) {
        $clients = \App\Client::getClientsWithUnrespondedQuestionsForSite($site);
        return view('client/questionlist', ['site' => $site, 'clients' => $clients]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($site)
    {
        $emails = Email::getUnrespondedEmailsForSite($site);
        return view('email/list', ['emails' => $emails, 'site' => $site]);
    }

    public function sendMail($site, EmailRequest $request) {

        $input = $request->all();
        $input['name'] = $input['email'];
        $client = $site->clients()->emailAddress($input['email'])->first();

        if ($client) {
            $input['name'] = $client->fullName;
        }


        $emailData = [
            'sent' => 1,
            'from_email' => $site->email,
            'from_name' => $input['sender'],
            'to_email' => $input['email'],
            'to_name' => $input['name'],
            'subject' => $input['subject'],
            'html_content' => $input['content'],
            'text_content' => '',
            'bounce' => 1

        ];

        $email = new Email($emailData);

        $result = false;

        if ($email->save()) {
            $result = $email->send();
        }

        return $result;
    }
}
