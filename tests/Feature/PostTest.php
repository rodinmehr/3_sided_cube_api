<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'cube@test.com',
            'password' => bcrypt('cubetest'),
        ]);
    }

    public function test_can_get_all_posts(): void
    {
        Post::factory()->count(3)->create();

        $response = $this->getJson('/api/posts');

        // $response->assertStatus(200)
        //     ->assertJsonCount(3);
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content', 'author', 'scheduled_at', 'published_at', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_can_create_post_with_auth(): void
    {
        Sanctum::actingAs($this->user);

        $postData = [
            'title' => 'Test Post Title',
            'content' => 'This is a test post content.',
            'author' => 'Test Author',
        ];

        $response = $this->postJson('/api/posts', $postData);

        // $response->assertStatus(201)
        //     ->assertJsonFragment($postData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'content', 'author', 'scheduled_at', 'published_at', 'created_at', 'updated_at'
                ]
            ])
            ->assertJsonFragment($postData);
    }

    public function test_cannot_create_post_without_auth(): void
    {
        $postData = [
            'title' => 'Test Post Title',
            'content' => 'This is a test post content.',
            'author' => 'Test Author',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(401);
    }

    public function test_can_get_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}");

        // $response->assertStatus(200)
        //     ->assertJsonFragment($post->toArray());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'content', 'author', 'scheduled_at', 'published_at', 'created_at', 'updated_at'
                ]
                ]);
    }

    public function test_can_update_post_with_auth(): void
    {
        Sanctum::actingAs($this->user);

        $post = Post::factory()->create();
        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
            'author' => 'Updated Author',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updatedData);

        // $response->assertStatus(200)
        //     ->assertJsonFragment($updatedData);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'title', 'content', 'author', 'scheduled_at', 'published_at', 'created_at', 'updated_at']
            ])
            ->assertJsonFragment($updatedData);
    }

    public function test_cannot_update_post_without_auth(): void
    {
        $post = Post::factory()->create();
        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
            'author' => 'Updated Author',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updatedData);

        $response->assertStatus(401);
    }

    public function test_can_delete_post_with_auth(): void
    {
        Sanctum::actingAs($this->user);

        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_cannot_delete_post_without_auth(): void
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(401);
    }

    public function test_can_login_and_get_token(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'cube@test.com',
            'password' => 'cubetest',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_can_logout(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully.']);
    }
}
