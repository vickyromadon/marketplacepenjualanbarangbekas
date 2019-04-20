<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
    	if( $request->isMethod('post') ){
    		$search;
            $status     = $request->status;
            $category   = $request->name_category;
            $start      = $request->start;
            $length     = $request->length;

            if( !empty($request->search) )
                $search = $request->search['value'];
            else
                $search = null;

            $column = [
                "product_name",
                "user_name",
                "category_name",
                "product_created_at",
                "view",
            ];

            $total = DB::table('products')->join('categories', 'products.category_id', '=', 'categories.id')
                    ->join('users', 'products.user_id', '=', 'users.id')
                    ->where("products.name", 'LIKE', "%$search%")
                    ->where("categories.name", 'LIKE', "%$category%")
                    ->where("products.status", 'LIKE', "$status%")
                    ->count();

            $data = DB::table('products')->join('categories', 'products.category_id', '=', 'categories.id')
                    ->join('users', 'products.user_id', '=', 'users.id')
                    ->select("products.id AS id",
                            "products.name AS product_name",
                            "users.name AS user_name",
                            "categories.name AS category_name",
                            "products.status AS product_status",
                            "products.created_at AS product_created_at",
                            "products.view AS view"
                        )
                    ->where("products.name", 'LIKE', "%$search%")
                    ->where("categories.name", 'LIKE', "%$category%")
                    ->where("products.status", 'LIKE', "$status%")
                    ->orderBy($column[$request->order[0]['column'] - 1], $request->order[0]['dir'])
                    ->skip($start)
                    ->take($length)
                    ->get();

            $response = [
                'data' => $data,
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $total
            ];

            return response()->json($response);
    	}

    	return $this->view(['categories' => Category::all()]);
    }

    public function show($id)
    {
        return $this->view(['data' => Product::find($id)]);
    }
}
