@extends('layouts.admin')


@section('content')


<h1 class="text-muted display-5 py-3">Tags Page</h1>

@include('partials.validation_errors')
@include('partials.session_message')
<div class="row">
    <div class="col-6">
        <form action="{{route('admin.tags.store')}}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Full Stack" aria-label="Button" name="name" id="name">
                <button class="btn btn-outline-secondary" type="submit">Add</button>
            </div>

        </form>
    </div>
    <div class="col-6">

        <div class="table-responsive-md">
            <table class="table table-light">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Posts Count</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($tags as $tag)
                    <tr class="">
                        <td scope="row">{{$tag->id}}</td>
                        <td>

                            <form action="{{route('admin.tags.update', $tag)}}" method="post">
                                @csrf
                                @method('PATCH')
                                <div class="input-group">
                                    <input class="form-control border-0 bg-transparent" type="text" name="name" id="name" value="{{$tag->name}}" aria-describedby="editInput-{{$tag->id}}">

                                    <span class="input-group-text border-0">
                                        <i class="fa-solid fa-pencil" id="editInput-{{$tag->id}}"></i>
                                    </span>
                                </div>
                                <small>Press enter to update the tag name</small>
                            </form>

                        </td>
                        <td>{{$tag->slug}}</td>
                        <td>
                            <span class="badge bg-dark">{{ $tag->posts->count()}}</span>

                        </td>
                        <td>
                            <form action="{{route('admin.tags.destroy', $tag)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-tractor"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr class="">
                        <td scope="row"> 👈 Add your first tag </td>
                    </tr>

                    @endforelse


                </tbody>
            </table>
        </div>


    </div>

</div>



@endsection