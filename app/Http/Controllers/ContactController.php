<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $formHelper = getFormHelper('contacts');
        $relation = getRelationKey('contacts');
        if(count($relation) > 0){
            $datas = Contact::with($relation)->latest()->paginate(10);
        } else {
            $datas = Contact::latest()->paginate(10);
        }
        $data = [
            "breadcrumbs" => [
                "parent" => "Master",
                "child" => "Contact",
            ],
            'formHelper' => $formHelper,
            "title" => "Contact",
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
        $array = getArrayPost($request, 'contacts');
        try {
            Contact::create($array);
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
        return Contact::find($id);
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit(Contact $city)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, string $id)
    {
        $array = getArrayPost($request, 'contacts');
        try {
            $data = Contact::find($id);
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
            Contact::destroy($id);
            afterPost($request);
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
