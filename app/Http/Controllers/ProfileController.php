<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $params = [
        ];

        return view('profile.index', $params);
    }

    public function edit()
    {
        $params = [
        ];

        return view('profile.edit', $params);
    }

    public function update(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(\Auth::user()->id)],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ],
            [],
            []
        );

        $user = \Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('profile.index')->with('message', 'プロフィールを更新しました。');
    }
}
