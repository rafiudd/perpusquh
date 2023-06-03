<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanItem;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index() {
        $loans = Loan::with('student', 'loan_items')->paginate(8);
        // dd($loans);
        return view('admin.loans.list', compact('loans'));
    }

    public function destroy(Request $request) {
        $student_id = $request->student_id;

        Student::find($student_id)->delete();
        return redirect('/dashboard/student-management');
    }

    public function create() {
        $students = Student::all();
        $books = Book::all();

        return view('admin.loans.create', compact('students', 'books'));
    }

    public function store(Request $request) {
        $loan = Loan::Create([
            'student_id' => $request->student_id,
            'status' => 'Sedang Dipinjam',
            'note' => $request->note,
            'return_date' => $request->return_date
        ]);

        for ($i=0; $i < count($request->book_id); $i++) { 
            $book = Book::where('id', '=', $request->book_id[$i])->first();
            $loan_items = LoanItem::Create([
                'student_id' => $request->student_id,
                'book_id' => $request->book_id[$i],
                'book_title' => $book->title,
                'loan_id' => $loan->id,
                'quantity' => '1'
            ]);
        }

        return redirect('/dashboard/loan-management');
    }

    public function edit(Request $request) {
        $student_id = $request->student_id;
        $student = Student::where('id', '=', $student_id)->first();

        return view('admin.loans.edit', compact('student'));
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
