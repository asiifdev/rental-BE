<?php

namespace App\Http\Controllers;

use App\Models\SliderSetting;
use Illuminate\Http\Request;

class SliderSettingController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $formHelper = getFormHelper('slider_settings');
        $relation = getRelationKey('slider_settings');
        if(count($relation) > 0){
            $datas = SliderSetting::with($relation)->latest()->paginate(10);
        } else {
            $datas = SliderSetting::latest()->paginate(10);
        }
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "SliderSetting",
            ],
            'formHelper' => $formHelper,
            "title" => "SliderSetting",
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
        $array = getArrayPost($request, 'slider_settings');
        try {
            SliderSetting::create($array);
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
        return SliderSetting::find($id);
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit(SliderSetting $city)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        $array = getArrayPost($request, 'slider_settings');
        try {
            $data = SliderSetting::find($id);
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
            SliderSetting::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
