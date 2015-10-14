<?php

namespace App\Http\Controllers;

use Mail;
use Hash;
use Validator;
use Response;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if ( ! \Auth::user()->isAdmin()) {
            abort(403);
        }
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
        if ( ! \Auth::user()->isAdmin()) {
            abort(403);
        }
        $user = new User;
        $sites = \App\Site::active()->get();

        return view('user/create', [
            'user' => $user,
            'userTypes' => $user->getUserTypes(),
            'userSiteIds' => $user->sites()->lists('listid')->toArray(),
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
        $this->validateUserInput($request);
        $input = $request->all();

        $password = str_random(10);

        $newUser = new User;
        $newUser->fill($input);
        if ((\Auth::user()->isAdmin()) && $input['type']) {
            $newUser->type = $input['type'];
        }
        $newUser->password = $password;

        $result = $newUser->save();

        // TODO return error
        if (!$newUser->id) {
            return 'Error: cannot save user to database';
        }

        if (isset($input['sites'])) {
            $newUser->sites()->sync($input['sites']);
        }

        $this->sendNewUserEmail($newUser, $password);

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
        if ( ! \Auth::user()->isAdmin()) {
            abort(403);
        }

        $sites = \App\Site::active()->get();
        return view('user/edit', [
                'user' => $user,
                'userTypes' => $user->getUserTypes(),
                'userSiteIds' => $user->sites()->lists('listid')->toArray(),
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
        $this->validateUserInput($request, $user);
        $input = $request->all();

        if ((\Auth::user()->isAdmin()) && $input['type']) {
            $user->type = $input['type'];
        }
        $user->update($input);


        if (isset($input['sites'])) {
            $user->sites()->sync($input['sites']);
        }

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
        if ( ! \Auth::user()->isAdmin()) {
            abort(403);
        }

        if (\Auth::user()->id == $user->id) {
            abort(403);
        }

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
                $m->to($user->email, $user->name)->subject('Your Tarot account!');
            }
        );
    }

    public function profile(Request $request) {
        if ($user = $request->user()) {
            return view('user/profile', ['user' => $user]);
        }
    }

    public function updateProfile(Request $request) {
        $loggedUser = $request->user();

        $this->validateUserInput($request, $loggedUser);

        $input = $request->all();

        if (!Hash::check($input['password'], $loggedUser->password)) {
            return response(json_encode(['password' => ['Incorrect password']]), 403);
        }

        $loggedUser->fill($input);
        if (isset($input['newpassword']) && $input['newpassword']) {
            $loggedUser->password = $input['newpassword'];
        }

        $loggedUser->save();

        return $loggedUser->toArray();
    }

    private function validateUserInput(Request $request, $user = null) {

        $rules = [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email'.($user ? ','.$user->id : ''),
            'type' => 'numeric|min:1',
            'password' => 'string|min:5',
            'newpassword' => 'string|min:5',
            'reppassword' => 'same:newpassword'
        ];

        return $this->validate($request, $rules);
    }
}

