<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Services\MeilisearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $books = MeilisearchService::search(
            model: Book::class,
            query: $request->input('query'),
            page: 1,
            attributesToHighlight: ['*'],
        )->getHits();

        $authors = MeilisearchService::search(
            model: Author::class,
            query: $request->input('query'),
            page: 1,
            attributesToHighlight: ['*'],
        )->getHits();

        return view('search', compact('books', 'authors'));
    }
}
