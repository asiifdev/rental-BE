<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;

class CompanySettingController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $formHelper = getFormHelper('company_settings');
        $relation = getRelationKey('company_settings');
        if(count($relation) > 0){
            $datas = CompanySetting::with($relation)->first();
        } else {
            $datas = CompanySetting::first();
        }
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "CompanySetting",
            ],
            'formHelper' => $formHelper,
            "title" => "CompanySetting",
            'data' => $datas,
        ];
        return view('templateEngine.oneData', $data);
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
        $array = getArrayPost($request, 'company_settings');
        // dd($array);
        try {
            $data = CompanySetting::first();
            $data->update($array);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return response()->json($array);
        }
    }

    /**
    * Display the specified resource.
    */
    public function show(string $id)
    {
        return CompanySetting::find($id);
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit(CompanySetting $city)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        $array = getArrayPost($request, 'company_settings');
        try {
            $data = CompanySetting::find($id);
            $data->update($array);
            afterPost($request);
            return redirect()->back();
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
            CompanySetting::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
