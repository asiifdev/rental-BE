<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\ProductResource;
use App\Models\Api\Brand;
use App\Models\Api\Product;
use App\Models\Api\ProductCategory;
use App\Models\Api\Stock;
use App\Models\Api\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $condition = [];
        $query = $request->query();
        if ($query) {
            // $condition['product_category_id'] = $id;
            foreach ($query as $key => $q) {
                $kecuali = [
                    'page',
                    'perPage',
                    'role'
                ];
                if (!in_array($key, $kecuali)) {
                    $condition[] = [
                        'column' => $key,
                        'operator' => 'LIKE',
                        'value' => '%' . $q . '%'
                    ];
                }
            }
        }
        $data = Product::with(['category', 'stocks', 'transactions'])->where($condition)->get();
        $result = [];
        $nomor = 1;
        foreach ($data as $key => $item) {
            $result[$key] = $item;
            $result[$key]->nomor = $nomor++;
        }

        $total = count($result);
        $per_page = $request->input("per_page") ?? 5;
        $current_page = $request->input("page") ?? 1;

        $starting_point = ($current_page * $per_page) - $per_page;

        $array = array_slice($result, $starting_point, $per_page, true);

        $result = new Paginator($array, $total, $per_page, $current_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        // return response()->json($result);
        // $products = Product::with(['category', 'stocks', 'transactions'])->get();

        return $this->sendResponse($result, 'Products retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->role != 'Super Admin') {
            return $this->sendError('Failed Permissions.', []);
        }
        unset($request['role']);

        $input = $request->all();

        $brand = $input['brand'] ?? "";
        $input['qty'] = $input['qty'] ?? 0;

        $validator = Validator::make($input, [
            'name' => 'required',
            'category_id' => 'required|exists:product_categories,id',
            'brand' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = Product::create($input);
        Stock::create(
            [
                'product_id' => $product->id,
                'qty' => $product->qty,
            ]
        );

        Transaction::create([
            'product_id' => $product->id,
            'user_input_id' => auth()->guard('api')->user()->id,
            'jenis' => 'in',
            'qty' => $product->qty
        ]);

        if ($brand && $brand != "") {
            $cek = Brand::where('name', $brand)->where('product_category_id', $input['category_id'])->first();
            if (!$cek) {
                Brand::create([
                    'name' => $brand,
                    'product_category_id' => $input['category_id']
                ]);
            }
        }

        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with(['category', 'stocks', 'transactions'])->find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if ($request->role != 'Super Admin') {
            return $this->sendError('Failed Permissions.', []);
        }
        unset($request['role']);

        $input = $request->all();
        $brand = $input['brand'] ?? "";

        $validator = Validator::make($input, [
            'name' => 'required',
            'category_id' => 'required|exists:product_categories,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($brand && $brand != "") {
            $cek = Brand::where('name', $brand)->where('product_category_id', $input['category_id'])->first();
            if (!$cek) {
                Brand::create([
                    'name' => $brand,
                    'product_category_id' => $input['category_id']
                ]);
            }
        }

        $product->name = $input['name'];
        $product->category_id = $input['category_id'];
        if (isset($input['qty'])) {
            $product->qty = $input['qty'];
        }
        if (isset($input['brand'])) {
            $product->brand = $input['brand'];
        }
        $product->save();

        Stock::where('product_id', $product->id)->update(
            [
                'qty' => $product->qty,
            ]
        );
        Transaction::where('product_id', $product->id)->update([
            'user_input_id' => auth()->guard('api')->user()->id,
            'jenis' => 'in',
            'qty' => $product->qty
        ]);

        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Product $product)
    {
        if ($request->role != 'Super Admin') {
            return $this->sendError('Failed Permissions.', []);
        }
        unset($request['role']);

        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
