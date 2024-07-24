<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $posts = Post::all();
        return $this->success($posts, 'Posts retrieved successfully');
    }

    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->error('Post not found', 404);
        }

        return $this->success($post, 'Post retrieved successfully');
    }

    public function store(StorePostRequest $request)
    {
        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return $this->success($post, 'Post created successfully', 201);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->error('Post not found', 404);
        }

        if ($post->user_id != auth()->id()) {
            return $this->error('Unauthorized', 403);
        }

        $post->update($request->all());

        return $this->success($post, 'Post updated successfully');
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->error('Post not found', 404);
        }

        if ($post->user_id != auth()->id()) {
            return $this->error('Unauthorized', 403);
        }

        $post->delete();

        return $this->success(null, 'Post deleted successfully');
    }
}
