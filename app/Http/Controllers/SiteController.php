<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\SiteRequest;
use App\Http\Controllers\Controller;
use App\Site;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $sites = Site::all();

        return view('site/list', ['sites' => $sites]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $site = new Site;

        return view('site/create', ['site' => $site]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(SiteRequest $request)
    {
        $input = $request->all();

        $newSite = Site::create($input);

        return $newSite;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($site)
    {
        return $this->edit($site);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($site)
    {
        return view('site/edit', ['site' => $site]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($site, SiteRequest $request)
    {
        $input = $request->all();

        $site->update($input);

        return $site;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($site)
    {
        $site->delete();
        return true;
    }
}
