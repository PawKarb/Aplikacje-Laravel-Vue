<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class LoginController extends Controller
{
    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('throttle:30,5')->only('login');
    }
    function login (Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6',

        ],[
            'email.exists'=>'Podany adres email nie istnieje!',
        ]);
        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json('', 204);
        }
        return response()->json(['error' => 'Nieudane logowanie!'], 401);
    }
    function logout(){
        Auth::logout();
        return response('');
    }
    public function register(Request $request){
        $request->validate([
            'name'=> 'required|min:3|max:30|unique:users',
            'email'=> 'required|email',
            'password'=> 'required|min:6|confirmed',
            'password_confirmation'=> 'required',
        ],
        [
            'name.unique'=>'Podana nazwa użytkownika już istnieje!',
        ]);
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
    }
    public function sendPasswordLink(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }
}

