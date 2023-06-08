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
            <option value="{{$category?->id}}" {{ $category?->id  == old('category_id', $post->category?->id) ? 'selected' : '' }}>{{$category?->name}}</option>
            @endforeach
        </select>
    </div>


    <div class="form-group">
        <p>Seleziona i tag:</p>
        @foreach ($tags as $tag)
        <div class="form-check @error('tags') is-invalid @enderror">
            <label class="form-check-label">
                @if($errors->any())
                {{-- se ci sono degli errori di validazione
                signifca che bisogna recuperare i tag selezionati
                tramite la funzione old(),
                la quale restituisce un array plain contenente solo gli id --}}

                <input name="tags[]" type="checkbox" value="{{ $tag->id }}" class="form-check-input" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>

                @else
                {{-- se non sono presenti errori di validazione
				significa che la pagina è appena stata aperta per la prima volta,
				perciò bisogna recuperare i tag dalla relazione con il post,
				che è una collection di oggetti di tipo Tag	--}}

                <input name="tags[]" type="checkbox" value="{{ $tag->id }}" class="form-check-input" {{ $post->tags->contains($tag) ? 'checked' : '' }}>
                @endif


                {{ $tag->name }}
            </label>

        </div>
        @endforeach
        @error('tags')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
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