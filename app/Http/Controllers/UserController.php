<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = [];

        $params['input'] = $request->input();

        $users = User::query();

        if ($request->filled('user_name')) {
            $users->where('name', 'LIKE', '%'.$request->user_name.'%');
        }
        if ($request->filled('email')) {
            $users->where('email', 'LIKE', '%'.$request->email.'%');
        }

        $params['users'] = $users->paginate(20);

        return view('user.index', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $params = [
            'user' => $user,
        ];

        return view('user.show', $params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $params = [
            'user' => $user,
        ];

        return view('user.edit', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate(
            [
                'role_admin' => '',
                'role_develop' => '',
                'role_editor' => '',
            ]
        );

        $user->role_admin = $request->role_admin;
        $user->role_develop = $request->role_develop;
        $user->role_editor = $request->role_editor;
        $user->save();

        return redirect()->route('user.show', $user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')->with('message', __('message.deleted'));
    }
}
