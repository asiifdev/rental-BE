<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formHelper = getFormHelper('users');
        $relation = getRelationKey('users');
        if (count($relation) > 0) {
            $datas = User::with($relation)->find(auth()->user()->id);
        } else {
            $datas = User::find(auth()->user()->id);
        }
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "User",
            ],
            'formHelper' => $formHelper,
            "title" => "User",
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
        $array = getArrayPost($request, 'users');
        // dd($array);
        try {
            $data = User::find(auth()->user()->id);
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
        return User::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $array = getArrayPost($request, 'users');
        // dd($array);
        try {
            $data = User::find($id);
            $data->assignRole($array['role']);
            unset($array['role']);
            if (isset($array['password']) && $array['password'] == NULL) {
                unset($array['password']);
            } else {
                $array['password'] = bcrypt($array['password']);
            }
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
            User::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
