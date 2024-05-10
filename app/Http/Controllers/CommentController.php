<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class CommentController extends Controller
{


    public function create($postId)
    {
        $post_id = Post::findOrFail($postId);

    
    return view('post.comment', ['post_id' => $postId]);
       
    }

    public function store(Request $request)
{
    try {
        // Validate the request
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'text' => 'required|string',
        ]);

        // Create a new Comment instance and save it
        $comment = new Comment();
        $comment->user_id = Auth::id(); // Assuming authenticated users
        $comment->post_id = $request->post_id;
        $comment->text = $request->text;
        $comment->save();

        return redirect()->route('posts.index')->with('success', 'Comment created successfully.');
    } catch (ValidationException $e) {
        // Handle validation errors
        return back()->withErrors($e->validator)->withInput();
    } catch (ModelNotFoundException $e) {
        // Handle model not found errors
        return back()->with('error', 'Post not found.');
    } catch (Exception $e) {
        // Handle other generic errors
        return back()->with('error', 'An unexpected error occurred. Please try again later.');
    }
}

public function show($postId)
{
    // Retrieve the post
    $post = Post::findOrFail($postId);

    // Retrieve comments associated with the post
    $comments = Comment::where('post_id', $postId)->get();

    //return $comments;

    // Pass post and comments data to the view
    return view('post.show_comments', compact('post', 'comments'));
}
}
