<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_author_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/authors');

        $response->assertStatus(200);
    }

    public function test_create_author_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/authors/create');

        $response->assertStatus(200);
    }

    public function test_edit_author_page(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();

        $response = $this->actingAs($user)->get("/authors/{$author->id}/edit");

        $response->assertStatus(200);
    }

    public function test_edit_author_page_does_not_load_for_guests(): void
    {
        $author = Author::factory()->create();

        $response = $this->get("/authors/{$author->id}/edit");
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_create_author_page_does_not_load_for_guests(): void
    {
        $response = $this->get('/authors/create');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_show_author_page(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();

        $response = $this->actingAs($user)->get("/authors/{$author->id}");

        $response->assertStatus(200);
    }

    public function test_show_author_page_does_not_load_for_guests(): void
    {
        $author = Author::factory()->create();

        $response = $this->get("/authors/{$author->id}");

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_create_author(): void
    {
        $user = User::factory()->create();

        $name = fake()->name();

        $response = $this->actingAs($user)->post('/authors', [
            "name" => $name,
        ]);

        $this->assertDatabaseHas('authors', [
            "name" => $name,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/authors');
    }

    public function test_update_author(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();

        $name = fake()->name();

        $this->assertDatabaseMissing('authors', [
            "name" => $name,
        ]);

        $response = $this->actingAs($user)->put("/authors/{$author->id}", [
            "name" => $name,
        ]);

        $this->assertDatabaseHas('authors', [
            "id" => $author->id,
            "name" => $name,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/authors');
    }

    public function test_delete_author(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();

        $this->assertDatabaseHas('authors', [
            "id" => $author->id,
        ]);

        $response = $this->actingAs($user)->delete("/authors/{$author->id}");

        $this->assertDatabaseMissing('authors', [
            "id" => $author->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/authors');
    }

    public function test_delete_author_does_not_work_for_guests(): void
    {
        $author = Author::factory()->create();

        $this->assertDatabaseHas('authors', [
            "id" => $author->id,
        ]);

        $response = $this->delete("/authors/{$author->id}");

        $this->assertDatabaseHas('authors', [
            "id" => $author->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
