<?php

namespace App\Http\Controllers\Owner;

use App\Models\User;
use App\Models\Product;
use App\Models\UsePoin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsePoinController extends Controller
{
    public function index(Request $request){
    	if( $request->isMethod('post') ){
            $search;
            $start = $request->start;
            $length = $request->length;

            if( !empty($request->search) )
                $search = $request->search['value'];
            else
                $search = null;

            $column = [
                "created_at",
            ];

            $total = UsePoin::with(['product'])
            		->where("user_id", '=', Auth::user()->id )
            		->count();

            $data = UsePoin::with(['product'])
            		->where("user_id", '=', Auth::user()->id )
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

    	return $this->view([
    		'products' => Product::where('user_id', Auth::user()->id)->where('type', Product::TYPE_SELL)->get(),
    	]);
    }

    public function store(Request $request){
    	$validator = $request->validate([
    		'product'		=> 'required',
    	]);

    	$product 		= Product::find($request->product);
    	$user 			= User::find($product->user_id);
    	$user->poin 	-= 2;
    	$user->save();

    	$usePoin 				= new UsePoin;
    	$usePoin->product_id 	= $product->id;
    	$usePoin->expired_date 	= date('d-m-Y', strtotime('+2 days', strtotime(date('Y-m-d'))));
    	$usePoin->user_id 		= $user->id;

    	if( !$usePoin->save() ){
            return response()->json([
                'success'   => false,
                'message'   => 'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
        else{
            return response()->json([
                'success'  => true,
                'message'  => 'Berhasil menggunakan poin'
            ]);
        }
    }
}
