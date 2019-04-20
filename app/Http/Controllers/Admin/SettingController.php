<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index(Request $request)
    {
    	if( $request->isMethod('post') ){
            $search;
            $start = $request->start;
            $length = $request->length;

            if( !empty($request->search) )
                $search = $request->search['value'];
            else
                $search = null;

            $column = [
                "name",
            ];

            $total = Category::where("name", 'LIKE', "%$search%")
            		->count();

            $data = Category::where("name", 'LIKE', "%$search%")
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
    	return $this->view();
    }

    public function update(Request $request, $id)
    {
    	$validator = $request->validate([
    		'price_buy'		=> 'required|numeric',
    		'price_sell'	=> 'required|numeric',
            'min_buy'       => 'required|numeric',
            'min_sell'      => 'required|numeric',
    	]);

    	$category 				= Category::find($id);
    	$category->price_buy 	= $request->price_buy;
    	$category->price_sell 	= $request->price_sell;
        $category->min_buy      = $request->min_buy;
        $category->min_sell     = $request->min_sell;
     	
     	if( $category->save() ){
	    	return response()->json([
	            'success'	=> true,
	            'message'	=> 'Berhasil Menentukan Harga'
	        ]);
     	}
     	else{
     		return response()->json([
	            'success'	=> false,
	            'message'	=> 'Gagal Menentukan Harga'
	        ]);	
     	}
    }
}
