<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return StudentResource::collection(Student::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'index' => 'regex:/^s[0-9][0-9][0-9]$/|unique:students,index',
            'email'=>'email|unique:students,email',
            'age' => 'required',
            'description' => 'nullable',
            'paid' => 'nullable'
            ]);

        $student = Student::create($data);

        return new StudentResource($student);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return new StudentResource($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $data=$request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'index' => "regex:/^s[0-9][0-9][0-9]$/ | unique:students,index, {$student->id}",
            'email' => "email|unique:students,email, {$student->id} ",
            'age' => 'required',
            'description' => 'nullable',
            'paid' => 'nullable'
            ]);

        $student->update($data);

        return new StudentResource($student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json('Deleted', 204);
    }
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['index', 'show']]);
    }
}
