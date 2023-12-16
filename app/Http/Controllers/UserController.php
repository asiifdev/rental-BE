<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $formHelper = getFormHelper('users');
        $data = User::get();
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "User",
            ],
            'formHelper' => $formHelper,
            "title" => "User",
            'data' => $data,
        ];
        return view('admin.master.users', $data);
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
        try {
            $user = User::create($array);
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
        try {
            $user = User::find($id);
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
            User::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
