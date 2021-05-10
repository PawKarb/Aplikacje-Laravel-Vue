<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    function login (Request $request) {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json('', 204);
        }
        return response()->json(['error' => 'Nieudane logowanie!'], 401);
    }
    function logout(Request $request){
        Auth::logout();
        return response('');
    }
    public function register(Request $request){
        $request->validate([
            'name'=> 'required',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|min:6|confirmed',
            'password_confirmation'=> 'required|min:6',
        ]);
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
    }
}
