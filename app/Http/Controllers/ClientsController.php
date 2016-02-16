<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ClientRequest;
use App\Http\Controllers\Controller;
use App\Client as Client;
use App\Email as Email;
use App\Template as Template;

class ClientsController extends Controller
{

    public function editClientByEmail($site, $emailAddress) {

        $sitesWithClient = $this->getSitesWithClientByEmail($emailAddress);

        // treat found client
        if ($sitesWithClient && ! $sitesWithClient->isEmpty()) {
            // try to find if the client if registered on the current site
            foreach ($sitesWithClient as $siteWClient) {
                if ( ! $siteWClient->clients || $siteWClient->clients->isEmpty()) {
                    continue;
                }

                if ($siteWClient->id === $site->id) {
                    return $this->edit($site, $siteWClient->clients->first());
                }
            }

            // return the first found client in other sites
/*            foreach ($sitesWithClient as $siteWClient) {
                if ( ! $siteWClient->clients || $siteWClient->clients->isEmpty()) {
                    continue;
                }

                return $this->edit($siteWClient, $siteWClient->clients->first());
            }*/
        }

        $templates = $site->templates()->ofCategory('email')->active()->orderBy('name')->get();
        $infocosts = $site->infocosts()->active()->default()->get();

        $email = Email::fromEmail($emailAddress)->toSite($site)->unresponded()->orderBy('sent_at')->first();

        $client = new Client(['listid' => $site->id]);
        $client->email = $emailAddress;
        $client->name = $email->from_name;

        if ($email) {
            $nextUrl =  "/sites/$site->id/nextemailbytime/".$email->sent_at->timestamp;
        }

        $countries = \App\Infocost::countries();

        $viewData = [
            'site' => $site,
            'client' => $client,
            'sites_with_client' => $sitesWithClient,
            'subscribtionsCount' => 0,
            'templates' => $templates,
            'infocosts' => $infocosts,
            'nextUrl' => $nextUrl,
            'countries' => $countries,
            'subject' => 'RE: ' . $email->subject,
            'templateCategory' => 'email',
        ];

        return view('client.createFromEmail', $viewData);

    }

    public function redirect()
    {
        $siteId = \Auth::user()->currentSiteId();

        return redirect("/sites/$siteId/clients");
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($site)
    {
        $clients = $site->clients()
                        ->with(['data.field', 'emails', 'sentEmails'])->limit(1)->get();

/*                        ->with(['emails' => function($query) {
                                return $query->limit(21);
                            }])
                        ->with(['sentEmails' => function($query) {
                                return $query->notBounced()->limit(1);
                            }])
                        ->get();*/

        return view('client.list', [
            'site' => $site,
            'clients' => $clients
        ]);
    }

    public function query($site, Request $request)
    {
        if (! empty($request->input('search')['value'])) {
            $clientsCount = $site->clients()->countBySearchTerm($request->input('search')['value']);

            $clients = $site->clients()->bySearchTerm($request->input('search')['value'])
                            ->take($request->input('length'))
                            ->skip($request->input('start'))
                            ->with('data.field')
                            ->with(['emails' => function($query) {
                                    return $query->limit(21);
                                }])
                            ->with(['sentEmails' => function($query) {
                                    return $query->notBounced()->limit(1);
                                }])
                            ->get();


        }
        else {
            $clientsCount = $site->clients()->count();

            $clients = $site->clients()
                            ->take($request->input('length'))
                            ->skip($request->input('start'))
                            ->with('data.field')
                            ->with(['emails' => function($query) {
                                    return $query->limit(21);
                                }])
                            ->with(['sentEmails' => function($query) {
                                    return $query->notBounced()->limit(1);
                                }])
                            ->get();
        }

        $tableData = [];
        $index = $request->input('start');
        foreach($clients as $client) {
            $tableData[] = [
                ++$index,
                $client->name,
                $client->emailaddress,
                $client->gender,
                $client->country,
                $client->confirmdate->format('d-m-Y'),
                '',
                '',
                '',
                $client->comment,
                $client->id
            ];
        }

        $result = [
            'draw' => 0 + $request->input('draw'),
            'recordsTotal' => $clientsCount,
            'recordsFiltered' => $clientsCount,
            'data' => $tableData,
            'request' => $request->all()
        ];

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($site)
    {
        // Passing siteid to new clients creates valid client fields for that site->form
        $client = new Client(['listid' => $site->id]);
        $countries = \App\Infocost::countries();

        return view('client.create', [
            'site' => $site,
            'client' => $client,
            'countries' => $countries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store($site, ClientRequest $request)
    {

        $input = $request->all();

        if (array_key_exists('siteids', $input) && is_array($input['siteids']) && ! empty($input)) {
            $siteids = $input['siteids'];
        }
        else {
            $siteids = [$site->id];
        }

        if (empty($siteids)) {
            abort(404);
        }

        foreach($siteids as $siteid) {
            $client = new Client(['listid' => $siteid, 'confirmdate' => time()]);
            $client->fill($input);
            $client->save();
        }

        return $client;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($site, $client, $templateCategory = 'email')
    {
        return $this->edit($site, $client, $templateCategory);
    }

    public function getSitesWithClientByEmail($email) {
        $sites_with_client = \Auth::user()->sites()->with(['clients' => function($query) use ($email) {
            $query->where('emailaddress', $email);
        }])->get();

        return $sites_with_client;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($site, $client, $templateCategory = 'email')
    {
        $sites_with_client = new \Illuminate\Database\Eloquent\Collection;
        if ($client) {
            $sites_with_client = $this->getSitesWithClientByEmail($client->emailaddress);
        }

        $subscribtionsCount = 0;

        if ( ! $sites_with_client->isEmpty()) {
            foreach($sites_with_client as $key => $site_wc) {
                if (! $site_wc->clients->isEmpty()) {
                    if ($site_wc->clients->first()->isSubscribed()) {
                        $site_wc->hasUserSubscribed = true;
                    }
                    $subscribtionsCount++;
                }
                else {
                    $site_wc->hasUserSubscribed = false;
                }
            }
        }

        $templates = Template::active()->ofCategory($templateCategory)->ofSite($site->id)->orderBy('name')->get();

        $infocosts = $site->infocosts()->active()->default()->get();

        $alertClientOpenedToSoon = false;

        if ($client and $client->opened_at) {
            $now = time();
            if ( 60 > $now - $client->opened_at->timestamp) {
                $alertClientOpenedToSoon = true;
            }

            $client->opened_at = $client->opened_at->now();
            $client->save();
        }

        $nextUrl = null;
        if ($client->confirmdate) {
            if ($templateCategory === 'question') {
               $nextUrl = "/sites/$site->id/nextquestion/$client->id";
            }

            if ($templateCategory === 'email') {
               $nextUrl = "/sites/$site->id/nextemail/$client->id";
            }
        }
        $countries = \App\Infocost::countries();

        $lastEmail = $client->emails()->orderBy('sent_at', 'desc')->first();

        $viewData = [
            'site' => $site,
            'client' => $client,
            'sites_with_client' => $sites_with_client,
            'subscribtionsCount' => $subscribtionsCount,
            'templates' => $templates,
            'infocosts' => $infocosts,
            'nextUrl' => $nextUrl,
            'alertClientOpenedToSoon' => $alertClientOpenedToSoon,
            'countries' => $countries,
            'templateCategory' => $templateCategory,
        ];

        if ($templateCategory == 'email' && ($lastEmail)) {
            $viewData['subject'] = 'RE: '.$lastEmail->subject;
        }
        else {
            $viewData['subject'] = $site->subject;
        }

        return view('client/edit', $viewData);
    }

    public function markClientEmailsAsResponded($site, $client) {
        if (!$client) {
            return false;
        }

        $client->setQuestionAnswered();

        return ['result' => $client->markEmailsAsResponded($site)];
    }


    public function markClientLastEmailAsUnresponded($site, $client) {
        if (!$client) {
            return false;
        }

        if ($client->emails->isEmpty()) {
            $client->setQuestionUnanswered();
        }

        return ['result' => $client->markLastEmailAsUnresponded($site)];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($site, $client, ClientRequest $request)
    {
        $input = $request->all();
        $client->update($input);
        return $client;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function subscribeClientToSite($client, $site) {
        $site_client = $site->getClientByEmail($client->email);

        if (!$site_client) {
            $site_client = $client->getClone($site->id);
        }

        $site_client->setAsSubscribed();
        $site_client->save();
        return true;
    }

    public function unsubscribeClientToSite($client, $site) {
        $site_client = $site->getClientByEmail($client->email);

        if ($site_client) {
            $site_client->setAsUnsubscribed();
            $site_client->save();
        }

        return true;
    }


    public function subscribe($site, $client, Request $request) {
        $subscribe_to_site_ids = $request->input('siteids');

        if (!$subscribe_to_site_ids) {
            $subscribe_to_site_ids = [];
        }

        $sites_with_client = $this->getSitesWithClientByEmail($client->email);

        foreach ($sites_with_client as $site_wc) {
            if (in_array($site_wc->id, $subscribe_to_site_ids)) {
                $this->subscribeClientToSite($client, $site_wc);
            }
            else {
                $this->unsubscribeClientToSite($client, $site_wc);

            }
        }

        return back();
    }
}
