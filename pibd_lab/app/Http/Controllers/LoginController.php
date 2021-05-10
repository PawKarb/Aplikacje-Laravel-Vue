<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    function login (Request $request) {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
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
}
