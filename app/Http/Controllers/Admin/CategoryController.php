<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
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
                "description",
                "created_at",
            ];

            $total = Category::where("name", 'LIKE', "%$search%")
            		->orWhere("description", 'LIKE', "%$search%")
            		->count();

            $data = Category::where("name", 'LIKE', "%$search%")
            		->orWhere("description", 'LIKE', "%$search%")
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

    public function store(Request $request)
    {
    	$validator = $request->validate([
    		'name'			=> 'required|string|unique:categories',
    		'description'	=> 'nullable|string',
    	]);

    	$category 				= new Category();
    	$category->name 		= $request->name;
    	$category->description 	= $request->description;

    	if( $category->save() ){
	    	return response()->json([
	            'success'   => true,
	            'message'   => 'Berhasil Menambahkan'
	        ]);
    	}
    	else{
    		return response()->json([
	            'success'   => false,
	            'message'   => 'Gagal Menambahkan'
	        ]);
    	}		
    }

    public function update(Request $request, $id)
    {
    	$validator = $request->validate([
    		'name'	=> 'required|string',
    		'description'	=> 'required|string',
    	]);

    	$category 			= Category::find($id);
    	$category->name 	= $request->name;
    	$category->description 	= $request->description;
     	
     	if( $category->save() ){
	    	return response()->json([
	            'success'	=> true,
	            'message'	=> 'Berhasil Merubah'
	        ]);
     	}
     	else{
     		return response()->json([
	            'success'	=> false,
	            'message'	=> 'Gagal Merubah'
	        ]);	
     	}
    }

    public function destroy($id)
    {
    	$category = Category::find($id);
    	if( $category->delete() ){
	    	return response()->json([
	            'success'	=> true,
	            'message'	=> 'Berhasil Menghapus'
	        ]);
    	}
    	else{
    		return response()->json([
	            'success'	=> false,
	            'message'	=> 'Gagal Menghapus'
	        ]);	
    	}
    }
}
