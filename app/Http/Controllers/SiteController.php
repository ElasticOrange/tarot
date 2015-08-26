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
        $infocosts = $site->infocosts()->orderBy('country')->orderBy('active', 'desc')->orderBy('default', 'desc')->get();

        return view('site/edit', ['site' => $site, 'infocosts' => $infocosts]);
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
        return redirect('sites');
    }

    public function change(Request $request) {
        $referrer = $request->server('HTTP_REFERER');
        $input = $request->all();

        if (isset($input['siteId']) and $input['siteId'] > 0) {
            $siteId = $input['siteId'];

            $redirect_url = preg_replace('/(.*\/sites)\/([0-9]*)(.*)/', '$1/'.$siteId.'$3' ,$referrer);

            \Auth::user()->setCurrentSiteId($siteId);

            return redirect($redirect_url);
        }

        return redirect($referrer);
    }
}
