@extends('layouts.layout')
@section('content')
    <h1>{{ $post->title ?? 'Single Page' }}</h1>
    <ul>
        @forelse ($comments as $comment)
            <li>{{ $comment->text }}</li>
        @empty

        @endforelse
    </ul>
    @auth
        <form action="{{route('single.comment',$post)}}" method="post">
            @csrf
            <label>
                <textarea name="text"></textarea>
            </label>
        </form>
    @endauth
@endsection
