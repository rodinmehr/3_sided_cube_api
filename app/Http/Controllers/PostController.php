<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    public function index(): JsonResponse
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    public function store(PostRequest $request): JsonResponse
    {
        try {
            $post = Post::create($request->validated());
            return response()->json($post, 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error creating the post.'], 500);
        }
    }

    public function show(Post $post): JsonResponse
    {
        return response()->json($post);
    }

    public function update(PostRequest $request, Post $post): JsonResponse
    {
        try {
            $post->update($request->validated());
            return response()->json($post);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating the post.'], 500);
        }
    }

    public function destroy(Post $post): JsonResponse
    {
        try {
            $post->delete();
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting the post.'], 500);
        }
    }
}
