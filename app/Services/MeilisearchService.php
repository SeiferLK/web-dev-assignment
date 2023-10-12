<?php

namespace App\Services;

use Meilisearch\Client as MeilisearchClient;
use Meilisearch\Search\SearchResult;

final class MeilisearchService
{

    public static function search(string $model, string $query, int $page = 1, int $perPage = 10, string $filter = null, string $sort = null, array $attributesToHighlight = null): SearchResult
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

        if ($attributesToHighlight) {
            $options["attributesToHighlight"] = $attributesToHighlight;
        }

        return $index->search(
            $query,
            $options,
        );
    }
}
