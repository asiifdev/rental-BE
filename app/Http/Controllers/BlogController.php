<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formHelper = getFormHelper('blogs');
        $relation = getRelationKey('blogs');
        if (count($relation) > 0) {
            $datas = Blog::with($relation)->latest()->paginate(10);
        } else {
            $datas = Blog::latest()->paginate(10);
        }
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "Blog",
            ],
            'formHelper' => $formHelper,
            "title" => "Blog",
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
        $array = getArrayPost($request, 'blogs');
        try {
            Blog::create($array);
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
        return Blog::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $array = getArrayPost($request, 'blogs');
        try {
            $data = Blog::find($id);
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
            Blog::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
