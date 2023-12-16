<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $formHelper = getFormHelper('tags');
        $relation = getRelationKey('tags');
        if(count($relation) > 0){
            $datas = Tag::with($relation)->latest()->paginate(10);
        } else {
            $datas = Tag::latest()->paginate(10);
        }
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "Tag",
            ],
            'formHelper' => $formHelper,
            "title" => "Tag",
            'data' => $datas,
        ];
        return view('templateEngine.default', $data);
    }

    /**
    * Show the form for creating a new resource.
    */
    public function create()
    {
        //
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        $array = getArrayPost($request, 'tags');
        try {
            Tag::create($array);
            return afterPost($request);
        } catch (\Exception $e) {
            return response()->json($array);
        }
    }

    /**
    * Display the specified resource.
    */
    public function show(string $id)
    {
        return Tag::find($id);
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit(Tag $city)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        $array = getArrayPost($request, 'tags');
        try {
            $data = Tag::find($id);
            $data->update($array);
            return afterPost($request);
        } catch (\Exception $e) {
            return response()->json($array);
        }
    }

    /**
    * Remove the specified resource from storage.
    */
    public function destroy(Request $request, string $id)
    {
        try {
            Tag::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
