<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;

class SearchController extends Controller
{


    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $books = Book::search(request('query'))->get();
        $authors = Author::search(request('query'))->get();

        return view('search', compact('books', 'authors'));
    }
}
