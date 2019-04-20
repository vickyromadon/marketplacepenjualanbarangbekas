<?php

namespace App\Http\Controllers\Member;

use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
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
                "product_name",
                "file_path",
                "price",
                "quantity",
                "total_price",
            ];

            $total = DB::table('carts')
            		->join('products', 'carts.product_id', '=', 'products.id')
            		->join('files', 'products.file_id', '=', 'files.id')
            		->where("products.name", 'LIKE', "%$search%")
            		->count();

            $data = DB::table('carts')
            		->join('products', 'carts.product_id', '=', 'products.id')
            		->join('files', 'products.file_id', '=', 'files.id')
            		->select("carts.id AS id",
                            "products.name AS product_name",
                            "carts.price AS price",
                            "files.path AS file_path",
                            "carts.quantity AS quantity",
                            "carts.total_price AS total_price"
                        )
            		->where("products.name", 'LIKE', "%$search%")
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

    	$carts = Cart::where('user_id', Auth::user()->id)
                ->orderBy('product_id', 'asc')
    			->get();

    	$total_price = 0;
    	foreach ($carts as $cart) {
    		$total_price += intval($cart->total_price);
    	}

    	return $this->view([
            'carts'       => $carts,
    		'total_price' => $total_price,
    	]);
    }

    public function store(Request $request){
        $user 		= User::find($request->id_user);
        $product 	= Product::find($request->id_product);

        $validator = $request->validate([
            'quantity' 	=> 'required|numeric|min:0|max:'. $product->quantity,
        ]);

        $checkCart = Cart::where('user_id', $user->id)
        				->where('product_id', $product->id)
        				->first();

        if($checkCart == null){
	        $cart 				= new Cart();
	        $cart->user_id 		= $user->id;
	        $cart->product_id 	= $product->id;
	        $cart->price 		= $product->price;
	        $cart->quantity 	= $request->quantity;
	        $cart->total_price 	= intval($product->price * $request->quantity);
	        
	        if(!$cart->save()){
	        	return response()->json([
		        	'success'   => false,
		            'message'   => 'Terjadi Kesalahan, Silahkan Coba Lagi',
	        	]);
	        }
	        else{
	        	return response()->json([
		        	'success'   => true,
		            'message'   => 'Berhasil Menambahkan Ke Keranjang Belanja',	
		        ]);
	        }
        }
        else{
        	$tempPrice = intval($product->price * $request->quantity);

        	$checkCart->price 		= $product->price;
        	$checkCart->quantity 	+= $request->quantity;
        	$checkCart->total_price += $tempPrice;

        	if(!$checkCart->save()){
	        	return response()->json([
		        	'success'   => false,
		            'message'   => 'Terjadi Kesalahan, Silahkan Coba Lagi',
	        	]);
	        }
	        else{
	        	return response()->json([
		        	'success'   => true,
		            'message'   => 'Berhasil Menambahkan Ke Keranjang Belanja',	
		        ]);
	        }
        }
    }

    public function update(Request $request){
    	$cart 				= Cart::find($request->id);
    	$cart->quantity 	= $request->quantity;
    	$cart->total_price 	= intval($cart->price * $request->quantity);

    	$product 			= Product::find($cart->product_id);

    	$validator = $request->validate([
            'quantity' 	=> 'required|numeric|min:0|max:'. $product->quantity,
        ]);

    	if(!$cart->save()){
    		return response()->json([
	        	'success'   => false,
	            'message'   => 'Terjadi Kesalahan, Silahkan Coba Lagi',
        	]);
    	}
    	else{
        	return response()->json([
	        	'success'   => true,
	            'message'   => 'Perubahan berhasil disimpan',	
	        ]);
        }
    }

    public function destroy($id)
    {
    	$cart = Cart::find($id);
    	
    	if( $cart->delete() ){
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
