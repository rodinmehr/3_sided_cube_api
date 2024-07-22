<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;


class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    // public function index(): JsonResponse
    public function index(): AnonymousResourceCollection
    {
        $posts = Post::all();
        // return response()->json($posts);
        return PostResource::collection($posts);
    }

    // public function store(PostRequest $request): JsonResponse
    public function store(PostRequest $request): PostResource
    {
        $post = Post::create($request->validated());
        // return response()->json($post, 201);
        return new PostResource($post);
    }

    // public function show(Post $post): JsonResponse
    public function show(Post $post): PostResource
    {
        // return response()->json($post);
        return new PostResource($post);
    }

    // public function update(PostRequest $request, Post $post): JsonResponse
    public function update(PostRequest $request, Post $post): PostResource
    {
        $post->update($request->validated());
        // return response()->json($post);
        return new PostResource($post);
    }

    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return response()->json(null, 204);
    }
}
