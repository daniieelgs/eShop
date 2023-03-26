<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function see(){

        $users = Auth::user()->role === config('app.admin_role') ? User::all() : null;

        return view('userControlPane', ["users" => $users]);
    }

    function update(UserRequest $req, $id){
        $user = User::find($id);

        if($user == null) abort(404, "User not found");

        $user->name = $req->input("name");
        $user->email = $req->input("email");

        $admin = $req->input("admin") ?? false;

        $user->role = $admin ? config('app.admin_role') : config('app.default_role');

        $user->save();

        return back();

    }

    function remove($id){
        $user = User::find($id);

        if($user == null) abort(404, "User not found");

        $user->delete();

        return back();

    }

    function resetPassword(Request $req, $id){

        $req->validate([
            'password' => 'required|min:8'
        ]);

        $user = User::find($id);

        if($user == null) abort(404, 'User not found');

        $user->password = Hash::make($req->input('password'));
        $user->save();

        return back();

    }
}
