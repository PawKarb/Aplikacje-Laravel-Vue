<?php
namespace App\Http\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordService{
    public function getEmailFromToken($token){
        $records =  DB::table('password_resets')->get();
        foreach ($records as $record) {
            if (Hash::check($token, $record->token) ) {
               return $record->email;
               break;
            }
        }
    }
    public function getExpirationFromToken($token){
        $records =  DB::table('password_resets')->get();
        foreach ($records as $record) {
            if (Hash::check($token, $record->token) ) {
               return $record->created_at;
               break;
            }
        }
    }
    public function deleteToken($email){
        DB::table('password_resets')->where('email',$email)->limit(1)->delete();
    }
}
