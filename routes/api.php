<?php

use App\Http\Controllers\API\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Route::get('/test', function () {

    // Option 1 con response()->json();
    return response()->json([
        'success' => true,
        'name' => 'fabio',
    ]);

    // Option 2 con array
       return [

           'success' => true,
           'name' => 'fabio',
       ];

    // Option 3 return a collection as json
    return Post::all();
}); */

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']);
