<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formHelper = getFormHelper('roles');
        $relation = getRelationKey('roles');
        if (count($relation) > 0) {
            $datas = Role::with($relation)->latest()->paginate(10);
        } else {
            $datas = Role::latest()->paginate(10);
        }
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "Role",
            ],
            'formHelper' => $formHelper,
            "title" => "Role",
            'data' => $datas,
        ];
        return view('templateEngine.noForm', $data);
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
        $array = getArrayPost($request, 'roles');
        try {
            Role::create($array);
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
        return Role::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $array = getArrayPost($request, 'roles');
        try {
            $data = Role::find($id);
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
            Role::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
