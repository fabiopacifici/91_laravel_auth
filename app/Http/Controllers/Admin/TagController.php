<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTagRequest $request)
    {

        $val_data = $request->validated();
        $val_data['slug'] = Str::slug($request->name);

        Tag::create($val_data);

        return to_route('admin.tags.index')->with('message', 'Tag Created Successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        //dd($request->all());
        $val_data = $request->validated();

        $val_data['slug'] = Str::slug($request->name);

        $tag->update($val_data);

        return to_route('admin.tags.index')->with('message', 'Tag Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return to_route('admin.tags.index')->with('message', 'Tag Deleted Successfully');
    }
}
