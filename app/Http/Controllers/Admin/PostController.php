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

    public function store(Request $request)
    {
        // request data (user_id,title,description,image,tags)
        if (auth()->user()) {
            // create post
            /** @var Post $post */
            $post = auth()->user()->posts()->create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'image' => $request->input('image'),
            ]);
            // attach post tags
            $post->tags()->attach($request->input('tags'));
        }
        // redirect to the post.index route with message
        return redirect(route('post.index'))->with('message', 'new post has been created');
    }

    public function edit(Post $post)
    {
        $tags = Tag::query()->latest()->get();
        return view('admin.posts.edit', compact('tags', 'post'));
    }

    public function update(Request $request, Post $post)
    {
        // request data (title,description,image,tags)
        // update the post
        $post->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $request->input('image'),
        ]);
        // sync post tags
        $post->tags()->sync($request->input('tags'));
        // redirect to the post.index route with a message
        return redirect(route('post.index'))->with('message', 'the post has been updated');
    }

    public function destroy(Post $post): void
    {
        //
    }
}
