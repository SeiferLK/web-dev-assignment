<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Meilisearch\Client as MeilisearchClient;
use Meilisearch\Search\SearchResult;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private function meiliSearch(string $model, string $query, int $page = 1, int $perPage = 10, string $filter = null, string $sort = null): SearchResult
    {
        $client = new MeilisearchClient(config("scout.meilisearch.host"), config("scout.meilisearch.key"));
        $index = $client->getIndex((new $model)->searchableAs());

        $options = [
            "offset" => ($page - 1) * $perPage,
            "limit" => $perPage,
        ];

        if ($filter) {
            $options["filter"] = [$filter];
        }

        if ($sort) {
            $options["sort"] = [$sort];
        }

        return $index->search(
            $query,
            $options,
        );
    }


    public function search(Request $request)
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

        $books = $this->meiliSearch(
            model: Book::class,
            query: $query,
            page: $page
        );

        $authors = $this->meiliSearch(
            model: Author::class,
            query: $query,
            page: $page,
        );

        return [
            "books" => $books->getHits(),
            "authors" => $authors->getHits()
        ];
    }
}
