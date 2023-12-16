<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Api\Brand;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class BrandController extends BaseController
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
        $data = Brand::with('category')->where($condition)->get();
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
}
