@extends('auth.layouts')
  
@section('content')
<!-- Display post content -->



        @Auth
        <form action="{{ route('comment.store') }}" method="POST">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post_id }}">
            <div class="form-group">
                <textarea class="form-control" name="text" rows="3" placeholder="Add a comment"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>
        
        @endAuth
    </div>
</div>

@endsection
