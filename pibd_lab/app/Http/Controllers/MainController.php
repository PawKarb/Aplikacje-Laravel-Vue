<?php

namespace App\Http\Controllers;

use App\Notifications\TaskNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MainController extends Controller
{
    function index(){
        return view('spa');
    }
    function sendNotification(User $user){
        $delay = Carbon::now()->addSeconds(10);
        $user->notify((new TaskNotification)->delay($delay));
    }
}
