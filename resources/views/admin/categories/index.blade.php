@extends('layouts.admin')


@section('content')


<h1 class="text-muted display-5 py-3">Categories Page</h1>

@include('partials.validation_errors')
@include('partials.session_message')
<div class="row">
    <div class="col-6">
        <form action="{{route('admin.categories.store')}}" method="post">
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

                    @forelse ($categories as $category)
                    <tr class="">
                        <td scope="row">{{$category->id}}</td>
                        <td>{{$category->name}}</td>
                        <td>{{$category->slug}}</td>
                        <td>
                            <span class="badge bg-dark">{{ $category->posts->count()}}</span>

                        </td>
                        <td>
                            <form action="{{route('admin.categories.destroy', $category)}}" method="post">
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
                        <td scope="row"> 👈 Add your first category </td>
                    </tr>

                    @endforelse


                </tbody>
            </table>
        </div>


    </div>

</div>



@endsection
