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
}
