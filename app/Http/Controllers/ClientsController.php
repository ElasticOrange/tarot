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
        $client = new Client;
        return view('client.create', ['site' => $site, 'client' => $client]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(ClientRequest $request)
    {
        $input = $request->all();

        $client = Client::create($input);

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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($site, $client)
    {
        return view('client/edit', ['site' => $site, 'client' => $client]);
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
}
