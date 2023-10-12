<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Tests\TestCase;

class ExportTest extends TestCase
{
    public function test_export_page_works(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/export');

        $response->assertStatus(200);
    }

    public function test_export_csv(): void
    {
        $user = User::factory()->create();

        // Clear books and authors
        Book::truncate();
        Author::truncate();

        $csvString = <<<'CSV'
        title,author
        "Eum illo quo officia.","Alexa Moen I"
        "Distinctio qui animi voluptas.","Zachery Kiehn"
        "Explicabo praesentium atque animi.","Royal Quitzon"
        "Beatae occaecati aliquam quam.","Dell Gleason"
        "Qui rerum doloremque porro dolores.","Katelin Larson"
        "Numquam minus.","Dr. Jolie Tremblay"
        "Reprehenderit iste minus fugit.","Emmet Heidenreich"
        "Inventore voluptatibus rerum illum.","Prof. Fernando Yost MD"
        "Ea nihil minus.","Mikel Johnston"
        "Ipsum maiores quisquam eligendi.","Elva O'Kon"
        CSV;

        // Loop through each line of the CSV and create the books and authors
        collect(explode("\n", $csvString))->each(function ($line, $key) {
            // if it's the header row, skip it
            if ($key === 0) {
                return;
            }
            // Replace the double quotes with nothing
            $line = str_replace('"', "", $line);
            [$title, $authorName] = explode(",", $line);

            $author = Author::factory()->create([
                "name" => $authorName,
            ]);

            Book::factory()->create([
                "title" => $title,
                "author_id" => $author->id,
            ]);
        });

        $response = $this->actingAs($user)
            ->post('/export/file', [
                "extension" => "csv",
                "export-data" => "title-author",
            ]);

        $response->assertStatus(200);

        $response->assertHeader("Content-type", "text/csv; charset=UTF-8");

        $response->assertStreamedContent($csvString . "\n");
    }

    public function test_export_xml(): void
    {
        $user = User::factory()->create();

        // Clear books and authors
        Book::truncate();
        Author::truncate();

        $xmlString = <<<'XML'
        <?xml version="1.0"?>
        <root><row><title>Eum illo quo officia.</title><author>Alexa Moen I</author></row><row><title>Distinctio qui animi voluptas.</title><author>Zachery Kiehn</author></row><row><title>Explicabo praesentium atque animi.</title><author>Royal Quitzon</author></row><row><title>Beatae occaecati aliquam quam.</title><author>Dell Gleason</author></row><row><title>Qui rerum doloremque porro dolores.</title><author>Katelin Larson</author></row><row><title>Numquam minus.</title><author>Dr. Jolie Tremblay</author></row><row><title>Reprehenderit iste minus fugit.</title><author>Emmet Heidenreich</author></row><row><title>Inventore voluptatibus rerum illum.</title><author>Prof. Fernando Yost MD</author></row><row><title>Ea nihil minus.</title><author>Mikel Johnston</author></row><row><title>Ipsum maiores quisquam eligendi.</title><author>Elva O'Kon</author></row></root>
        XML;

        // Iterate through each row of the XML and create the books and authors
        $xml = new \SimpleXMLElement($xmlString);
        foreach ($xml->row as $row) {
            $title = (string) $row->title;
            $authorName = (string) $row->author;

            $author = Author::factory()->create([
                "name" => $authorName,
            ]);

            Book::factory()->create([
                "title" => $title,
                "author_id" => $author->id,
            ]);
        }

        $response = $this->actingAs($user)
            ->post('/export/file', [
                "extension" => "xml",
                "export-data" => "title-author",
            ]);

        $response->assertStatus(200);

        $response->assertHeader("Content-type", "text/xml; charset=UTF-8");

        $response->assertStreamedContent($xmlString . "\n");
    }
}
