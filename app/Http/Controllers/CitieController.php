<?php

namespace App\Http\Controllers;

use App\Models\Citie;
use Illuminate\Http\Request;

class CitieController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $formHelper = getFormHelper('cities');
        $data = Citie::get();
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "Citie",
            ],
            'formHelper' => $formHelper,
            "title" => "Citie",
            'data' => $data,
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
        $array = getArrayPost($request, 'cities');
        try {
            $user = Citie::create($array);
            $user->assignRole($request->role);
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
        return Citie::find($id);
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit(Citie $city)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        $array = getArrayPost($request, 'cities');
        try {
            $user = Citie::find($id);
            $user->update($array);
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
            Citie::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
