<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::all();

        return view('user/list', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $user = new User;
        $sites = \App\Site::active()->get();

        return view('user/create', [
            'user' => $user,
            'userTypes' => $user->getUserTypes(),
            'sites' => $sites
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(UserRequest $request)
    {
        $input = $request->all();

        $newUser = User::create($input);

        return $newUser;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($user)
    {
        return $this->edit($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($user)
    {
        $sites = \App\Site::active()->get();
        return view('user/edit', [
                'user' => $user,
                'userTypes' => $user->getUserTypes(),
                'sites' => $sites
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($user, UserRequest $request)
    {
        $input = $request->all();

        $user->update($input);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($user)
    {
        $user->delete();
        return true;
    }
}
