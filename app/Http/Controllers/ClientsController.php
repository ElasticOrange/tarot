<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ClientRequest;
use App\Http\Controllers\Controller;
use App\Client as Client;

class ClientsController extends Controller
{

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
        $clients = $site->clients()->with('data.field')->get();

        return view('client.list', [
            'site' => $site,
            'clients' => $clients
        ]);
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

        return view('client.create', ['site' => $site, 'client' => $client]);
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

        $input['listid'] = $site->id;

        $client = new Client(['listid' => $site->id]);

        $client->fill($input);

        $client->save();

        return $client;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($site, $client)
    {
        return $this->edit($site, $client);
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
    public function edit($site, $client)
    {
        $sites_with_client = $this->getSitesWithClientByEmail($client->emailaddress);


        if (!$sites_with_client->isEmpty()) {
            foreach($sites_with_client as $key => $site_wc) {
                if (!$site_wc->clients->isEmpty() and ($site_wc->clients->first()->isSubscribed())) {
                    $site_wc->hasUserSubscribed = true;
                }
                else {
                    $site_wc->hasUserSubscribed = false;
                }
            }
        }
        return view('client/edit', ['site' => $site, 'client' => $client, 'sites_with_client' => $sites_with_client]);
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

        $sites_with_client = $this->getSitesWithClientByEmail($client->email);

        foreach ($sites_with_client as $site) {
            if (in_array($site->id, $subscribe_to_site_ids)) {
                $this->subscribeClientToSite($client, $site);
            }
            else {
                $this->unsubscribeClientToSite($client, $site);

            }
        }

        return true;
    }
}
