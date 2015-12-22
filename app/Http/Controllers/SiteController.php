<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\SiteRequest;
use App\Http\Controllers\Controller;
use App\Site;
use App\Emailbox as Emailbox;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (\Auth::user()->type !=  \App\User::ADMINISTRATOR) {
            abort(403);
        }
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
        if (\Auth::user()->type !=  \App\User::ADMINISTRATOR) {
            abort(403);
        }
        $site = new Site;
        $allCountries = \App\Infocost::countries();
        return view('site/create', ['site' => $site, 'countries' => $allCountries]);
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
        if (\Auth::user()->type !=  \App\User::ADMINISTRATOR) {
            abort(403);
        }
        $infocosts = $site->infocosts()->orderBy('country')->orderBy('active', 'desc')->orderBy('default', 'desc')->get();

        $emailboxes = Emailbox::orderBy('name')->get();

        $allCountries = \App\Infocost::countries();

        return view('site/edit', [
            'site' => $site,
            'infocosts' => $infocosts,
            'emailboxes' => $emailboxes,
            'countries' => $allCountries
        ]);
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
        if (\Auth::user()->type !=  \App\User::ADMINISTRATOR) {
            abort(403);
        }
        $site->delete();
        return redirect('sites');
    }

    public function change(Request $request) {
        if (! $request->siteId or ! ($request->siteId > 0)) {
            return back();
        }

        $referrer = $request->server('HTTP_REFERER');
        $redirect_url = preg_replace('/(.*\/sites)\/([0-9]*)(.*)/', '$1/'.$request->siteId.'$3' ,$referrer);

        \Auth::user()->setCurrentSiteId($request->siteId);
        return redirect($redirect_url);
    }
}
