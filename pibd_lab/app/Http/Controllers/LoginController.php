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
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6'
        ],[
            'email.required' =>'Email jest wymagany!',
            'password.required' =>'Hasło jest wymagane!',
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
            'name'=> 'required|min:3|max:30|unique:users',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|min:6|confirmed',
            'password_confirmation'=> 'required',
        ],
        [
            'name.unique'=>'Podana nazwa użytkownika już istnieje!',
            'name.required'=>'Nazwa użytkownika jest wymagana!',
            'email.unique'=>'Podany adres email już istnieje!',
            'email.required' =>'Email jest wymagany!',
            'password.required' =>'Hasło jest wymagane!',
            'password_confirmation.required' =>'Powtórz hasło jest wymagane!',
        ]);
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
    }
}
