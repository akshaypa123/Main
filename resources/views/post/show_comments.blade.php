@extends('auth.layouts')
   
@section('content')
  
<h1>Comments for Post: {{ $post->title }}</h1>

@foreach ($comments as $comment)
    <div>
        <p><strong>User:</strong> {{ $comment->user ? $comment->user->name : 'Akshay' }}</p>

        <p><strong>Comment:</strong> {{ $comment->text }}</p>
    </div>
@endforeach

@endsection