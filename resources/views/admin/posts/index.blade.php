@extends('layouts.layout')
@section('content')
    <h1>Post Index Page</h1>
    @forelse ($posts as $post)
        <h1>{{ $post->title }}</h1>
    @empty

    @endforelse
@endsection
