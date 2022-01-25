@extends('layouts.layout')
@section('content')
    <h1>{{ $post->title ?? 'Single Page' }}</h1>
    <ul>
        @forelse ($comments as $comment)
            <li>{{ $comment->text }}</li>
        @empty

        @endforelse
    </ul>
@endsection
