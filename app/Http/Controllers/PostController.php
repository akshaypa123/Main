<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::latest()->paginate(5);

            //return view('post.index',compact('posts'));

            return view('post.index', [
                'posts' => $posts,
                'i' => ($posts->currentPage() - 1) * $posts->perPage(),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            // You can customize the error handling as per your application's requirements
        }
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'title' => 'required',
                'content' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        
            // Get the authenticated user's ID
            $user_id = Auth::id();
            
            // Create an array with the input data
            $input = $request->all();
            
            // Assign the authenticated user's ID to the 'user_id' field in the input array
            $input['user_id'] = $user_id;
            
            // Store the post data into the database
            $post = Post::create($input);
            
            // Check if the post was successfully created
            if ($post) {
                // Redirect to the posts index page with a success message
                return redirect()->route('posts.index')->with('success', 'Post created successfully.');
            } else {
                // If the post creation failed, log an error and redirect back with an error message
                Log::error('Error storing post: Post creation failed.');
                return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred while creating the post. Please try again.']);
            }
        } catch (\Exception $e) {
            // If an exception occurred, log the error and redirect back with an error message
            Log::error('Error storing post: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred while creating the post. Please try again.']);
        }
    }

     
    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('post.show',compact('post'));
    }
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('post.edit',compact('post'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        try {
            $request->validate([
                'title' => 'required',
                'content' => 'required',
            ]);
    
            $input = $request->all();
    
            if ($image = $request->file('image')) {
                $destinationPath = 'image/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $input['image'] = "$profileImage";
            } else {
                unset($input['image']);
            }
    
            $post->update($input);
    
            return redirect()->route('posts.index')
                            ->with('success', 'Post updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
            // You can also log the error or handle it in other ways as per your application's requirement
        }
    }
    
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
     
        return redirect()->route('posts.index')
                        ->with('success','Post deleted successfully');
    }
}
