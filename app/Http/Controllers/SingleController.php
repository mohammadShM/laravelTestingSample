<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SingleController extends Controller
{

    public function index(Post $post)
    {
        $comments = $post->comments()->latest()->paginate(15);
        return view('single', compact('post', 'comments'));
    }

    public function comment(Request $request, Post $post): array
    {
        $request->validate([
            'text' => 'required',
        ]);
        $post->comments()->create([
            'user_id' => auth()->user()->id,
            'text' => $request->get('text'),
        ]);
        // return redirect()->route('single', $post->id);
        return [
            'created' => true,
        ];
    }

}
