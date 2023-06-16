<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {

        //$posts = Post::all();
        //$posts = Post::with(['category', 'tags', 'user'])->orderByDesc('id')->get();
        $posts = Post::with(['category', 'tags', 'user'])->orderByDesc('id')->paginate(8);

        return response()->json([
            'success' => true,
            'posts' => $posts,
        ]);
    }

    public function show($slug)
    {
        $post = Post::with(['category', 'tags', 'user'])->where('slug', $slug)->first();
        //dd($post);

        if ($post) {

            return response()->json([
                'success' => true,
                'result' => $post,
            ]);

        } else {
            return response()->json([
                'success' => false,
                'result' => 'Post not found 404',
            ]);
        }

    }
}
