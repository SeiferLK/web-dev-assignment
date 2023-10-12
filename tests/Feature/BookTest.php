<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Tests\TestCase;

class BookTest extends TestCase
{
    public function test_index_page_loads_correctly(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/books');

        $response->assertStatus(200);
    }

    public function test_create_page_loads_correctly(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/books/create');

        $response->assertStatus(200);
    }

    public function test_show_page_loads_correctly(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($user)->get("/books/{$book->id}");

        $response->assertStatus(200);
    }

    public function test_edit_page_loads_correctly(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($user)->get("/books/{$book->id}/edit");

        $response->assertStatus(200);
    }

    public function test_edit_page_does_not_load_for_guests(): void
    {
        $book = Book::factory()->create();

        $response = $this->get("/books/{$book->id}/edit");

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_create_page_does_not_load_for_guests(): void
    {
        $response = $this->get('/books/create');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_create_book_with_new_author(): void
    {
        $user = User::factory()->create();
        $title = 'The Lord of the Rings';
        $author = 'J.R.R. Tolkien';

        $response = $this->actingAs($user)->post('/books', [
            'title' => $title,
            'search' => $author,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/books');

        $this->assertDatabaseHas('books', [
            'title' => $title,
        ]);
        $this->assertDatabaseHas('authors', [
            'name' => $author,
        ]);
    }

    public function test_create_book_with_existing_author(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();

        $title = 'The Lord of the Rings';

        $response = $this->actingAs($user)->post('/books', [
            'title' => $title,
            'search' => $author->name,
            "author_id" => $author->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/books');

        $this->assertDatabaseHas('books', [
            'title' => $title,
        ]);

        $book = Book::where('title', $title)->orderBy("id", "desc")->first();

        $this->assertTrue($book->author_id === $author->id, "Book author is not the same as the one specified");
    }

    public function test_update_book(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $initialAuthor = $book->author;

        $newTitle = fake()->sentence(3);

        $response = $this->actingAs($user)->put("/books/{$book->id}", [
            'title' => $newTitle,
            "search" => $book->author->name,
            "author_id" => $book->author->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/books');

        $this->assertTrue($book->title !== $newTitle, "Book title is the same as the one specified");

        $this->assertTrue($book->author_id === $initialAuthor->id, "Book author is not the same as the one specified");
    }

    public function test_delete_book(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
        ]);

        $response = $this->actingAs($user)->delete("/books/{$book->id}");
        $response->assertStatus(302);
        $response->assertRedirect('/books');

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }
}
