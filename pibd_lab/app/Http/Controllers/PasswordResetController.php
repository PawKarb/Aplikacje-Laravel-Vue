<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordResetController extends Controller
{
    use ResetsPasswords;
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
            return response()->json('Aktualne haslo nie moze byc takie same jak poprzednie',422);
        }else{
            return $this->resetPassword($user, $request->password);
        }
    }
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->setRememberToken(Str::random(60));
        $user->save();
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
