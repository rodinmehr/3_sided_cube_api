<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_posts(): void
    {
        Post::factory()->count(3)->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_post(): void
    {
        $postData = [
            'title' => 'Test Post Title',
            'content' => 'This is a test post content.',
            'author' => 'Test Author',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJsonFragment($postData);
    }

    public function test_can_get_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonFragment($post->toArray());
    }

    public function test_can_update_post(): void
    {
        $post = Post::factory()->create();
        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
            'author' => 'Updated Author',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);
    }

    public function test_can_delete_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
