<?php

namespace App\Http\Controllers;

use App\Models\Katalog;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $formHelper = getFormHelper('katalogs');
        $relation = getRelationKey('katalogs');
        // dd($formHelper);
        if(count($relation) > 0){
            $datas = Katalog::with($relation)->latest()->paginate(10);
        } else {
            $datas = Katalog::latest()->paginate(10);
        }
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "Katalog",
            ],
            'formHelper' => $formHelper,
            "title" => "Katalog",
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
        $array = getArrayPost($request, 'katalogs');
        try {
            Katalog::create($array);
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
        return Katalog::find($id);
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit(Katalog $city)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        $array = getArrayPost($request, 'katalogs');
        try {
            $data = Katalog::find($id);
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
            Katalog::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
