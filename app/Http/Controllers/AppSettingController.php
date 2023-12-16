<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $formHelper = getFormHelper('app_settings');
        $relation = getRelationKey('app_settings');
        if(count($relation) > 0){
            $datas = AppSetting::with($relation)->latest()->first();
        } else {
            $datas = AppSetting::latest()->first();
        }
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "AppSetting",
            ],
            'formHelper' => $formHelper,
            "title" => "AppSetting",
            'data' => $datas,
        ];
        return view('admin.app_settings.index', $data);
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
        $request['_method'] = 'PATCH';
        $this->update($request);
        return redirect()->back();
    }

    /**
    * Display the specified resource.
    */
    public function show(string $id)
    {
        return AppSetting::find($id);
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit(AppSetting $city)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request)
    {
        $array = getArrayPost($request, 'app_settings');
        try {
            $data = AppSetting::latest()->first();
            $data->update($array);
            afterPost($request);
            return redirect()->back();
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
            AppSetting::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
