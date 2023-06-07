<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Models\Category;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->paginate(8);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $categories = Category::orderByDesc('id')->get();

        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        //dd($request->all());
        // validate the request
        $val_data =  $request->validated();
        //dd($val_data);

        // generate the title slug
        $slug = Post::generateSlug($val_data['title']);
        //dd($slug);
        $val_data['slug'] = $slug;
        //dd($val_data);

        // Create the new Post
        Post::create($val_data);
        // redirect back
        return to_route('admin.posts.index')->with('message', 'Post Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::orderByDesc('id')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //dd($request->all());

        $val_data = $request->validated();
        //dd($val_data);

        /* TODO:
        What happens if i update the post title ?
        */
        // Checks if the request has a key called title
        //dd($request->has('title'));

        // generate the title slug
        $slug = Post::generateSlug($val_data['title']);
        //dd($slug);
        $val_data['slug'] = $slug;
        //dd($val_data);


        $post->update($val_data);

        return to_route('admin.posts.index')->with('message', 'Post: ' . $post->title . 'Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return to_route('admin.posts.index')->with('message', 'Post: ' . $post->title . 'Deleted');
    }
}
