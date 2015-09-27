<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\EmailboxRequest as EmailboxRequest;
use App\Http\Controllers\Controller;

use App\Emailbox as Emailbox;

class EmailboxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $emailboxes = Emailbox::with('sites')->get();
        return view('emailbox.list', ['emailboxes' => $emailboxes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $emailbox = new Emailbox();
        return view('emailbox.create', ['emailbox' => $emailbox]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(EmailboxRequest $request)
    {
        $input = $request->all();
        $emailbox = new Emailbox();
        $emailbox->fill($input);
        $emailbox->save();
        return $emailbox;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($emailbox)
    {
        return $this->edit($emailbox);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($emailbox)
    {
        return view('emailbox.edit', ['emailbox' => $emailbox]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(EmailboxRequest $request, $emailbox)
    {
        $input = $request->all();
        $emailbox->update($input);
        return $emailbox;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($emailbox)
    {
        $emailbox->delete();

        return redirect()->action('EmailboxController@index');
    }
}
