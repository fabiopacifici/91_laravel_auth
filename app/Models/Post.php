<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'cover_image', 'slug'];

    public static function generateSlug($title)
    {
        return Str::slug($title, '-');
    }
}
