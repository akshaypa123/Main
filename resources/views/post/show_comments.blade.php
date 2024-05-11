@extends('auth.layouts')
   
@section('content')
  
<h1>Comments for Post: {{ $post->title }}</h1>

@foreach ($comments as $comment)
    <div>
        @if ($comment->user)
        <p><strong>User:</strong> {{ $comment->user->name }}</p>
    @else
        <p><strong>User:</strong> Unknown</p>
    @endif


        <p><strong>Comment:</strong> {{ $comment->text }}</p>
    </div>
@endforeach

@endsection