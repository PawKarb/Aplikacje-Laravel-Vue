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
        $user->notify(new TaskNotification);
    }
}
