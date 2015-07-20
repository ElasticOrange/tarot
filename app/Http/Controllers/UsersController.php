<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;

use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::all();

        return view('user/list', ['users' => $users, 'userTypes' => User::getUserTypes()]);
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
        $password = str_random(10);

        $newUser = new User;
        $newUser->fill($input);
        $newUser->password = bcrypt($password);

        $result = $newUser->save();

        if ($newUser->id) {
            $this->sendNewUserEmail($newUser, $password);
        }

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
        return redirect('users');
    }

    private function sendNewUserEmail($user, $password)
    {


        Mail::send('emails.usernew',
            [
                'user' => $user,
                'password' => $password
            ],
            function ($m) use ($user) {
                $m->to($user->email, $user->name)->sender('admin@tarot.com', 'Tarot Administrator')->from('admin@tarot.com')->subject('Your Tarot account!');
            }
        );
    }
}
