<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::paginate(8);

        // dd($books);
        return view('admin.books.list', compact('books'));
    }
}
