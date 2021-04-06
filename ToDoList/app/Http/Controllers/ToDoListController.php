<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToDoList;
use Carbon\Carbon;
use Laravel\Ui\Presets\React;
use Illuminate\Support\Facades\DB;

class ToDoListController extends Controller
{
    public function getlist(){
        $lists = ToDoList::all();
        return $lists;
    }
    public function list(){
        return view('todolist');
    }
    public function saveList(Request $request){
        $validated = $request->validate([
            'title' => 'required|min:3|unique:to_do_lists,title',
            'data_zakonczenia' => 'required',
        ]);
        $list = new ToDoList();
        $list->title = $request->title;
        $list->text = $request->text;
        $list->data_utworzenia = Carbon::now();
        $list->data_zakonczenia = $request->data_zakonczenia;
        $list->is_end = 0;
        $list->timestamps = false;
        $list->save();
        return back();
    }
    public function destroyList($id){
        ToDoList::destroy($id);
    }
    public function makeDone(Request $request,$id){
        DB::update('update to_do_lists set is_end = ? where id = ?', [$request->done,$id]);
    }
    public function editList(Request $request, $id){
        $validated = $request->validate([
            'title' => 'required|min:3',
            'data_zakonczenia' => 'required',
        ]);
        DB::update('update to_do_lists set title = ?, text = ?, data_zakonczenia = ? where id = ?', [$request->title,$request->text,$request->data_zakonczenia,$id]);
    }
}
