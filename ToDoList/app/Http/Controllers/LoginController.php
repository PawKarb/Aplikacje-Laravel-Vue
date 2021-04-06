<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function index(){
        return view("login");
    }
    public function sendLogin(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Niepoprawne dane'],
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }
}
