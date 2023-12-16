<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $formHelper = getFormHelper('galleries');
        $relation = getRelationKey('galleries');
        if(count($relation) > 0){
            $datas = Gallery::with($relation)->latest()->paginate(10);
        } else {
            $datas = Gallery::latest()->paginate(10);
        }
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "Gallery",
            ],
            'formHelper' => $formHelper,
            "title" => "Gallery",
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
        $array = getArrayPost($request, 'galleries');
        try {
            Gallery::create($array);
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
        return Gallery::find($id);
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit(Gallery $city)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        $array = getArrayPost($request, 'galleries');
        try {
            $data = Gallery::find($id);
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
            Gallery::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
