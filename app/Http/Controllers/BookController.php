<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $params = request()->validate([
            "sort" => ["nullable", "in:id,name,created_at,updated_at"],
            "order" => ["nullable", "in:asc,desc"],
        ]);

        $column = $params["sort"] ?? "id";
        $direction = $params["order"] ?? "asc";

        $paginatedBooks = Book::with("author")
            ->orderBy($column, $direction)
            ->cursorPaginate(perPage: 10)
            ->withQueryString();

        return view("books.index", [
            "books" => $paginatedBooks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("books.create", [
            "authors" => Author::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "title" => "required",
            "search" => "required",
            "author_id" => ["nullable", "exists:authors,id"]
        ]);

        // Create an author, if no identifier was specified
        $authorId = $validated["author_id"] ?? Author::firstOrCreate(["name" => $validated["search"]])->id;

        Book::create([
            "title" => $validated["title"],
            "author_id" => $authorId,
        ]);

        return redirect()->route("books.index")->with([
            "success-notification" => "Successfully created book"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view("books.edit", [
            "book" => $book,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            "title" => "required",
            "search" => "required",
            "author_id" => ["nullable", "exists:authors,id"]
        ]);

        // Create an author, if no identifier was specified
        $authorId = $validated["author_id"] ?? Author::firstOrCreate(["name" => $validated["search"]])->id;

        $book->update([
            "title" => $validated["title"],
            "author_id" => $authorId,
        ]);

        return redirect()->route("books.index")->with([
            "success-notification" => "Successfully updated book"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route("books.index");
    }
}
