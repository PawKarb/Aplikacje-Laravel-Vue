<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Service\ResetPasswordService;

class PasswordResetController extends Controller
{
    use ResetsPasswords;
    protected $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService){
        $this->resetPasswordService = $resetPasswordService;
    }
    public function callResetPassword(Request $request)
    {
        $email = $this->resetPasswordService->getEmailFromToken($request->token);
        $user = User::where('email',$email) -> first();
        $request->validate([
            'password'=> 'required|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/|confirmed',
            'password_confirmation'=> 'required',
            'token' => 'required'
        ]);
        return $this->resetPassword($user, $request->password);
    }
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->setRememberToken(Str::random(60));
        $user->save();
        $this->resetPasswordService->deleteToken($user->email);
        event(new PasswordReset($user));
    }
    public function checkToken(Request $request){
        $tokenExp = $this->resetPasswordService->getExpirationFromToken($request->token);
        $expiredAt = Carbon::now()->subSeconds(config('auth.passwords.users.expire')*60);
        if(Carbon::parse($tokenExp)->gte($expiredAt) && !$tokenExp){
            return 0;
        }else{
            return 1;
        }
        return 0;
    }
}
