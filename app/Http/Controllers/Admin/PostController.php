<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //dd(Auth::user(), Auth::id());

        $posts = Auth::user()->posts()->orderByDesc('id')->paginate(8);

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
        $tags = Tag::orderByDesc('id')->get();

        //dd($tags);
        return view('admin.posts.create', compact('categories', 'tags'));
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

        $val_data['user_id'] = Auth::id();
        //dd($val_data);


        if ($request->hasFile('cover_image')) {
            $image_path = Storage::put('uploads', $request->cover_image);
            //dd($image_path);
            $val_data['cover_image'] = $image_path;
        }


        //dd($val_data);
        // Create the new Post
        $new_post = Post::create($val_data);

        // Attach the checked tags
        if ($request->has('tags')) {
            $new_post->tags()->attach($request->tags);
        }

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
        $tags = Tag::orderByDesc('id')->get();


        if (Auth::id() === $post->user_id) {
            return view('admin.posts.edit', compact('post', 'categories', 'tags'));
        }
        abort(403);
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



        if ($request->hasFile('cover_image')) {
            //dd('here');

            //if post->cover_image
            // delete the previous image

            if ($post->cover_image) {
                Storage::delete($post->cover_image);
            }

            // Save the file in the storage and get its path
            $image_path = Storage::put('uploads', $request->cover_image);
            //dd($image_path);
            $val_data['cover_image'] = $image_path;
        }




        $post->update($val_data);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

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
        //detach all relationships with tags in the pivot table (only if cascadeOnDelete is not in the db migration)
        //$post->tags()->sync([]);

        // remove the image from the storage
        if ($post->cover_image) {
            Storage::delete($post->cover_image);
        }
        $post->delete();
        return to_route('admin.posts.index')->with('message', 'Post: ' . $post->title . 'Deleted');
    }
}
