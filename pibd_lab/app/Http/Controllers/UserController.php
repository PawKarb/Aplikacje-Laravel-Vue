<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
                'name'=> 'nullable|min:3|max:30|unique:users',
                'email'=> 'nullable|email|unique:users',
            ],
            [
                'name.unique'=>'Podana nazwa użytkownika już istnieje!',
                'email.unique'=>'Podany adres email już istnieje!',
            ]);
        $user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json('Deleted', 204);
    }
    public function updatePassword(Request $request){
        $password = Auth::user()->password;
        $request->validate([
            'actual_password'=> 'required|min:6',
            'password'=> 'required|min:6|confirmed',
            'password_confirmation'=> 'required',
        ]);

        if(Hash::check($request->actual_password, $password)){
            if($request->actual_password == $request->password){
                return response()->json('Aktualne hasło nie może być takie same jak poprzednie',422);
            }else{
                DB::update('update users set password = ? where id = ?', [Hash::make($request->password), Auth::user()->id]);
            }
        }else{
            return response()->json('Aktualne hasło nie jest poprawne',422);
        }
    }
}
