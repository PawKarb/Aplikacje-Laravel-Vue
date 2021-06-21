<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    protected $redirectTo = "/login";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:50,1')->only('verify', 'resend');
    }
        /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ],[
            'email.exists'=>'Podany adres email nie istnieje!',
        ]);
        $user = User::where('email',$request->email) -> first();
        if ($user->email_verified_at != null) {

            return response()->json('Konto juz jest zweryfikowane',422);
        }

        $user->sendEmailVerificationNotification();

        if ($request->wantsJson()) {
            return response()->json('Email z weryfikacją zostal wyslany', 204);
        }
    }


    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }
        if ($user->email_verified_at != null)
            return response()->json('Konto juz jest zweryfikowane',422);

        if ($user->markEmailAsVerified()){
            event(new Verified($user));
            return redirect($this->redirectPath())->with('verified', true);
        }

    }
}
