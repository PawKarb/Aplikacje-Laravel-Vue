<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
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
        $data = $request->validate([
                'name'=> 'required|min:3|max:30|unique:users',
                'email'=> 'required|email|unique:users',
                'password'=> 'required|min:6|confirmed',
                'password_confirmation'=> 'required',
            ],
            [
                'name.unique'=>'Podana nazwa użytkownika już istnieje!',
                'email.unique'=>'Podany adres email już istnieje!',
            ]);
        $user->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return response()->json('Deleted', 204);
    }
}
