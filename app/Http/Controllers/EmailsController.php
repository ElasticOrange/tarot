<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
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
}
