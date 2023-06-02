<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index() {
        $students = Student::paginate(8);

        return view('admin.students.list', compact('students'));
    }

    public function destroy(Request $request) {
        $student_id = $request->student_id;

        Student::find($student_id)->delete();
        return redirect('/dashboard/student-management');
    }

    public function create() {
        return view('admin.students.create');
    }

    public function store(Request $request) {
        $input = $request->all();

        Student::create($input);
        return redirect('/dashboard/student-management');
    }

    public function edit(Request $request) {
        $student_id = $request->student_id;
        $student = Student::where('id', '=', $student_id)->first();
        // dd($student);

        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request) {
        $student_id = $request->student_id;
        $input = $request->all();

        Student::find($student_id)->update($input);
        return redirect("/dashboard/student-management");
    }

    public function search(Request $request) {
		$keyword = $request->keyword;
        $students = Student::paginate(8);

        if($keyword) {
            $students = Student::where('name', 'LIKE', "%".$keyword."%")->orWhere('nisn', 'LIKE', "%".$keyword."%")->paginate(8);
        }

        return view('admin.students.list', compact('students'));
	}
}
