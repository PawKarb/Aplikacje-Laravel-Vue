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
        if($request->name && !$request->email){
            $user->update($request->only('name','first_name','last_name','address','born_date'));
        }else if($request->email && !$request->name){
            $user->update($request->only('email','first_name','last_name','address','born_date'));
        }else{
            $user->update($request->all());
        }
    }
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
