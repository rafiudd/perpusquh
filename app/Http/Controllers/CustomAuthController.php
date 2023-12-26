<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use App\Models\LoanItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;

class CustomAuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function customLogin(Request $request)
    {

            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = auth()->user();

                return redirect()->intended('/dashboard/book-management')->withSuccess('Signed in');
            }

            return redirect("/")->with('error','Incorrect credentials');

    }

    public function registration()
    {
        return view('auth.register');
    }

    public function customRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required|unique:users',
        ]);

        $data = $request->all();
        $check = $this->create($data);
        // dd($check);

        return redirect("/login")->withSuccess('Success register');
    }

    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'role'  => 'customer',
        'phone' => $data['phone']
      ]);
    }

    public function dashboard()
    {
        if(Auth::check()){
            return view('/');
        }

        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function signOut() {
        Session::flush();
        Auth::logout();

        return Redirect('/');
    }

    public function notFound()
    {
        return view('auth.404');
    }

    public function notFoundAdmin()
    {
        return view('admin.404');
    }

    public function welcome() {
        $books = Book::orderBy('title', 'ASC')->paginate(8);
        $mostLoanedBook = LoanItem::select('book_id', 'book_title', \DB::raw('COUNT(loan_id) as loan_count'))
        ->groupBy('book_id', 'book_title') // Include book_title in the GROUP BY
        ->orderByDesc('loan_count')
        ->take(5) // Limit to the top 5 results
        ->get();

        return view('welcome', compact('books', 'mostLoanedBook'));
    }
}
