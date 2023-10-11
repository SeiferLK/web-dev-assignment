<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $params = request()->validate([
            "sort" => ["nullable", "in:id,name,created_at,updated_at"],
            "order" => ["nullable", "in:asc,desc"],
        ]);

        $column = $params["sort"] ?? "id";
        $direction = $params["order"] ?? "asc";

        $paginatedAuthors = Author::query()->orderBy($column, $direction)->cursorPaginate(perPage: 10)->withQueryString();

        return view("authors.index", [
            "authors" => $paginatedAuthors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("authors.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
        ]);

        Author::create($validated);

        return redirect()->route("authors.index");
    }

    /**
     * Display the specified resource.
     * 
     * GET /authors/1
     */
    public function show(Author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        return view("authors.edit", [
            "author" => $author,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
        ]);

        $author->update($validated);

        return redirect()->route("authors.index");
    }

    /**
     * Remove the specified resource from storage.
     * 
     * DELETE /authors/1
     */
    public function destroy(Author $author)
    {
        $author->delete();

        return redirect()->route("authors.index");
    }
}
