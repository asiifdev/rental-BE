<?php

namespace App\Http\Controllers;

use App\Models\KatalogCategory;
use Illuminate\Http\Request;

class KatalogCategoryController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $formHelper = getFormHelper('katalog_categories');
        $relation = getRelationKey('katalog_categories');
        if(count($relation) > 0){
            $datas = KatalogCategory::with($relation)->latest()->paginate(10);
        } else {
            $datas = KatalogCategory::latest()->paginate(10);
        }
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "KatalogCategory",
            ],
            'formHelper' => $formHelper,
            "title" => "KatalogCategory",
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
        $array = getArrayPost($request, 'katalog_categories');
        try {
            KatalogCategory::create($array);
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
        return KatalogCategory::find($id);
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit(KatalogCategory $city)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        $array = getArrayPost($request, 'katalog_categories');
        try {
            $data = KatalogCategory::find($id);
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
            KatalogCategory::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
