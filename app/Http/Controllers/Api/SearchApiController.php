<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Services\MeilisearchService;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    public function search(Request $request): array
    {
        $request->validate([
            "query" => "required|string",
            "page" => "nullable|integer",
            // "perPage" => "nullable|integer",
            // "filter" => "nullable|string",
            // "sort" => "nullable|string",
        ]);

        $query = $request->input("query");
        $page = $request->input("page", 1);

        $books = MeilisearchService::search(
            model: Book::class,
            query: $query,
            page: $page
        );

        $authors = MeilisearchService::search(
            model: Author::class,
            query: $query,
            page: $page,
        );

        return [
            "books" => $books->getHits(),
            "authors" => $authors->getHits()
        ];
    }

    public function searchAuthors(Request $request)
    {
        $request->validate([
            "query" => "string"
        ]);

        $authors = MeilisearchService::search(
            model: Author::class,
            query: $request->input("query"),
            page: 1,
        );

        return $authors->getHits();
    }
}
