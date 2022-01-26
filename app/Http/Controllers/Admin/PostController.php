<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::query()->latest()->paginate(15);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $tags = Tag::query()->latest()->get();
        return view('admin.posts.create', compact('tags'));
    }

    public function store(Request $request): void
    {
        //
    }

    public function edit(Post $post)
    {
        $tags = Tag::query()->latest()->get();
        return view('admin.posts.edit', compact('tags', 'post'));
    }

    public function update(Request $request, Post $post): void
    {
        //
    }

    public function destroy(Post $post): void
    {
        //
    }
}
