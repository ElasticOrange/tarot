<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmailRequest;
use App\Http\Controllers\Controller;
use App\Email as Email;

function getNextClient($collection, $currentClient = null) {
    if (!$collection) {
        return false;
    }

    if ($collection->isEmpty()) {
        return false;
    }

    if (!$currentClient) {
        return $collection[0];
    }

    $aMinuteAgo = time() - 60;

    foreach ($collection as $index => $item) {
        if (($item->confirmdate->timestamp < $currentClient->confirmdate->timestamp)
        and ($item->opened_at->timestamp < $aMinuteAgo)) {
            return $item;
        }
    }

    return $collection[0];
}


function getNextEmail($collection, $currentEmail = null) {
    if (!$collection) {
        return false;
    }

    if ($collection->isEmpty()) {
        return false;
    }

    if (!$currentEmail) {
        return $collection[0];
    }

    foreach ($collection as $index => $item) {
        if (!$item->client) {
            continue;
        }
        if ($item->sent_at->timestamp < $currentEmail->sent_at->timestamp) {
            return $item;
        }
    }

    return $collection[0];
}


class EmailsController extends Controller
{
    public function lastEmails($site, $clientEmailAddress, $emailCount = 5) {
        $emails = Email::forEmailAddressAndSite($clientEmailAddress, $site)->notBounced()->latest()->limit($emailCount)->with('attachments')->get();
        return $emails;
    }


    public function unrespondedQuestions($site) {
        $clients = \App\Client::getClientsWithUnrespondedQuestionsForSite($site);
        return view('client/questionlist', [
            'site' => $site,
            'clients' => $clients
        ]);
    }

    public function nextQuestionForSite($site, $currentClient) {
        $clients = \App\Client::getClientsWithUnrespondedQuestionsForSite($site);

        $client = getNextClient($clients, $currentClient);

        if (!$client) {
            return redirect()->action('EmailsController@unrespondedQuestions', [$site]);
        }

        return redirect("/sites/$site->id/clients/$client->id/question");
    }

    public function nextUnrespondedClientForSite($site, $currentClient = null) {
        $emails = Email::getUnrespondedEmailsForSite($site);

        $clientLastEmail = $currentClient->getFirstUnrespondedEmail();

        $nextEmail = getNextEmail($emails, $clientLastEmail);
        return redirect("/sites/$site->id/clients/".$nextEmail->client->id);
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

        if ($result) {
            Email::markEmailsToSiteAsResponded($input['email'], $site);
        }

        return ['result' => $result];
    }
}
