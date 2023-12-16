<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $formHelper = getFormHelper('blog_categories');
        $relation = getRelationKey('blog_categories');
        if(count($relation) > 0){
            $datas = BlogCategory::with($relation)->latest()->paginate(10);
        } else {
            $datas = BlogCategory::latest()->paginate(10);
        }
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "BlogCategory",
            ],
            'formHelper' => $formHelper,
            "title" => "BlogCategory",
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
        $array = getArrayPost($request, 'blog_categories');
        try {
            BlogCategory::create($array);
            return afterPost($request);
        } catch (\Exception $e) {
            $array['error'] = $e->getMessage();
            return response()->json($array);
        }
    }

    /**
    * Display the specified resource.
    */
    public function show(string $id)
    {
        return BlogCategory::find($id);
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit(BlogCategory $city)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        $array = getArrayPost($request, 'blog_categories');
        try {
            $data = BlogCategory::find($id);
            $data->update($array);
            return afterPost($request);
        } catch (\Exception $e) {
            $array['error'] = $e->getMessage();
            return response()->json($array);
        }
    }

    /**
    * Remove the specified resource from storage.
    */
    public function destroy(Request $request, string $id)
    {
        try {
            BlogCategory::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
