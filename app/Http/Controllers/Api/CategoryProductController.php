<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\CategoryProductResource;
use App\Models\Api\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ProductCategory::with(['products', 'brands'])->get();
        return $this->sendResponse(CategoryProductResource::collection($data), 'Product Categories retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->role != 'Super Admin') {
            return $this->sendError('Failed Permissions.', []);
        }
        unset($request['role']);

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $category = ProductCategory::create($input);

        return $this->sendResponse(new CategoryProductResource($category), 'Product Categories created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = ProductCategory::with(['products', 'brands'])->find($id);

        if (is_null($category)) {
            return $this->sendError('Product Category not found.');
        }

        return $this->sendResponse(new CategoryProductResource($category), 'Product retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCategory $category)
    {
        if ($request->role != 'Super Admin') {
            return $this->sendError('Failed Permissions.', []);
        }
        unset($request['role']);

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $category->name = $input['name'];
        $category->save();

        return $this->sendResponse(new CategoryProductResource($category), 'Product Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ProductCategory $category)
    {
        if ($request->role != 'Super Admin') {
            return $this->sendError('Failed Permissions.', []);
        }
        unset($request['role']);

        $category->delete();

        return $this->sendResponse([], 'Product Category deleted successfully.');
    }
}
