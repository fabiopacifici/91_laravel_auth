@extends('layouts.admin')


@section('content')

<h1 class="py-3">Edit {{$post->title}}</h1>


@include('partials.validation_errors')

<form action="{{route('admin.posts.update', $post)}}" method="post">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" aria-describedby="titleHelper" placeholder="Learn php" value="{{ old('title', $post->title) }}">
        <small id="titleHelper" class="form-text text-muted">Type the post title max 150 characters - must be unique</small>
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">Categories</label>
        <select class="form-select @error('category_id') is-invalid @enderror" name=" category_id" id="category_id">
            <option value="">Select a category</option>
            @foreach ($categories as $category)
            <option value="{{$category->id}}" {{ $category->id  == old('category_id', $post->category->id) ? 'selected' : '' }}>{{$category->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="cover_image" class="form-label">Image</label>
        <input type="text" class="form-control @error('cover_image') is-invalid @enderror" name="cover_image" id="cover_image" aria-describedby="cover_imageHelper" placeholder="Learn php" value="{{ old('cover_image', $post->cover_image) }}">
        <small id="cover_imageHelper" class="form-text text-muted">Type the post cover_image max 150 characters - must be unique</small>
    </div>

    <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea class="form-control @error('content') is-invalid @enderror" name="content" id="content" rows="3">{{old('content', $post->content)}}</textarea>
    </div>


    <button type="submit" class="btn btn-dark">Update</button>

</form>


@endsection
