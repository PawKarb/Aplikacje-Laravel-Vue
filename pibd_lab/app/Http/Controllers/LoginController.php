<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('throttle:30,5')->only('login');
    }
    function login (Request $request) {
        $data = $request->validate([
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
    function logout(Request $request){
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
    public function callResetPassword(Request $request)
    {
        $email = $this->getEmailFromToken($request->token);
        $user = User::where('email',$email) -> first();
        $request->validate([
            'password'=> 'required|min:6|confirmed',
            'password_confirmation'=> 'required',
            'token' => 'required'
        ]);
        if(Hash::check($request->password,$user->password)){
            return response()->json('Aktualne hasło nie może być takie same jak poprzednie',422);
        }else{
            return $this->resetPassword($user, $request->password);
        }
    }
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->update();
        $user->setRememberToken(Str::random(60));
        event(new PasswordReset($user));
    }
    protected static function getEmailFromToken($token){
        $records =  DB::table('password_resets')->get();
        foreach ($records as $record) {
            if (Hash::check($token, $record->token) ) {
               return $record->email;
            }
        }
    }
}
