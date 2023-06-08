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
        $loan_id = $request->loan_id;
        $loans = Loan::with('student')->where('id', '=', $loan_id)->first();
        $loan_items = LoanItem::with('book')->where('loan_id', '=', $loan_id)->get();

        return view('admin.loans.edit', compact('loans', 'loan_items'));
    }

    public function update(Request $request) {
        $student_id = $request->student_id;
        $input = $request->all();

        Student::find($student_id)->update($input);
        return redirect("/dashboard/student-management");
    }

    public function search(Request $request) {
		$keyword = $request->keyword;
        $loans = Loan::with('student', 'loan_items')->paginate(8);
        $users = Student::orderBy('created_at', 'DESC')->where('name', 'LIKE', "%".$keyword."%")->get();
        $user_id = array();

        foreach ($users as $user) {
            array_push($user_id, $user->id);
        }

        if($keyword) {
            $loans = Loan::whereIn('student_id', $user_id)->with('student', 'loan_items')->paginate(8);
        }

        return view('admin.loans.list', compact('loans'));
	}

    public function approve(Request $request) {
        $loan_id = $request->loan_id;

        Loan::find($loan_id)->update([
            'status' => 'Telah Dikembalikan'
        ]);
        return redirect('/dashboard/loan-management');
    }
}
