<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function index(): View
    {
        return view('export.index');
    }

    public function file(): StreamedResponse
    {
        $validated = request()->validate([
            "export-data" => ["required", "in:title-author,title,author"],
            "extension" => ["required", "in:csv,xml"],
        ]);

        $extension = $validated["extension"];

        $data = match ($validated["export-data"]) {
            "title-author" => Book::with("author")->get()->map(fn ($book) => [
                "title" => $book->title,
                "author" => $book->author->name,
            ])->toArray(),
            "title" => Book::select("title")->get()->map(fn ($book) => ["title" => $book->title])->toArray(),
            "author" => Author::select("name")->get()->map(fn ($author) => ["author" => $author->name])->toArray(),
            default => [],
        };

        $headers = match ($extension) {
            "csv" => [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=export.csv",
            ],
            "xml" => [
                "Content-type" => "text/xml",
                "Content-Disposition" => "attachment; filename=export.xml",
            ],
            default => [],
        };

        $fileName = match ($validated["export-data"]) {
            "title-author" => "title-author",
            "title" => "title",
            "author" => "author",
            default => "export",
        };

        return response()->streamDownload(function () use ($data, $extension) {
            $file = fopen("php://output", "w");
            if ($extension === "csv") {
                fputcsv($file, array_keys($data[0]));
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
            } else {
                $xml = new \SimpleXMLElement('<root/>');
                foreach ($data as $row) {
                    $xmlRow = $xml->addChild('row');
                    foreach ($row as $key => $value) {
                        $xmlRow->addChild($key, $value);
                    }
                }
                fwrite($file, $xml->asXML());
            }
            fclose($file);
        }, "$fileName-export.$extension", $headers);
    }
}
