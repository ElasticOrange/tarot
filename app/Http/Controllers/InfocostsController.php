<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\InfocostRequest;
use App\Http\Controllers\Controller;
use App\Infocost;

class InfocostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($site)
    {
        $infocosts = $site->infocosts()->orderBy('country')->get(); //->orderBy('active')->orderBy('default')


        return view('infocost/table');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($site)
    {
        $infocost = new Infocost;
        $allCountries = $infocost->countries();
        return view('infocost/create', ['site' => $site, 'infocost' => $infocost, 'countries' => $allCountries]);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store($site, InfocostRequest $request)
    {
        $input = $request->all();
        $newInfocost = new Infocost;
        $newInfocost->fill($input);
        $newInfocost->site_id = $site->id;
        $newInfocost->save();
        return $newInfocost;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($site, $infocost)
    {
        return $this->edit($site, $infocost);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($site, $infocost)
    {

        return view('infocost/edit', ['site' => $site, 'infocost' => $infocost, 'countries' => $infocost->countries()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($site, $infocost, InfocostRequest $request)
    {
        $input = $request->all();

        if (isset($input['default']) && isset($input['country']) && isset($input['active'])) {
            $this->clearDefaultCountryInfocostForSite($site, $input['country']);
        }
        else {
            $input['default'] = 0;
        }

        $infocost->update($input);

        return $infocost;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($site, $infocost)
    {
        $infocost->delete();
        return true;
    }


    public function clearDefaultCountryInfocostForSite($site, $country) {
        return $site->infocosts()->where('country', $country)->update(['default' => 0]);
    }
}
