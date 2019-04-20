<?php

namespace App\Http\Controllers\Owner;

use App\Models\User;
use App\Models\File;
use App\Models\Product;
use App\Models\Delivery;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
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
                "quantity",
                "created_at",
                "status"
            ];

            $total = DB::table('deliveries')
            		->join('products', 'deliveries.product_id', '=', 'products.id')
            		->join('users', 'deliveries.user_id', '=', 'users.id')
            		->join('transactions', 'deliveries.transaction_id', '=', 'transactions.id')
            		->where("users.id", '=', Auth::user()->id)
            		->where("products.name", 'LIKE', "%$search%")
            		->count();

            $data = DB::table('deliveries')
            		->join('products', 'deliveries.product_id', '=', 'products.id')
            		->join('users', 'deliveries.user_id', '=', 'users.id')
            		->join('transactions', 'deliveries.transaction_id', '=', 'transactions.id')
            		->select(
            			'deliveries.id AS id',
            			'products.name AS product_name',
            			'deliveries.quantity AS quantity',
            			'deliveries.status AS status',
            			'deliveries.created_at AS created_at'
            		)
            		->where("users.id", '=', Auth::user()->id)
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

    	return $this->view();
    }

    public function show($id){
    	$delivery = Delivery::find($id);

    	return $this->view([
    		'delivery' => $delivery,
    	]);
    }

    public function update(Request $request){
    	$validator = $request->validate([
            'number_proof'	=> 'required|string|max:191',
            'delivery_at'   => 'required|date',
            'arrive_at'     => 'required|date',
            'proof'  	 	=> 'required|mimes:jpeg,jpg,png|max:5000',
        ]);

        $delivery 				= Delivery::find($request->id);
        $delivery->number_proof = $request->number_proof;
        $delivery->delivery_at 	= $request->delivery_at;
        $delivery->arrive_at 	= $request->arrive_at;
        $delivery->status  		= Delivery::STATUS_DELIVERY;

        if( $request->proof != null ){
    		$filename  = $request->file('proof')->getClientOriginalName();
	        $path      = $request->file('proof')->store('proof_delivery/' . Auth::user()->id);
	        $extension = $request->file('proof')->getClientOriginalExtension();
	        $size      = $request->file('proof')->getClientSize();

	        $file            = new File();
	        $file->filename  = time() . '_' . $filename;
	        $file->title     = $filename;
	        $file->path      = $path;
	        $file->extension = $extension;
	        $file->size      = $size;
	        $file->save();

	        $delivery->file()->associate($file);
    	}

    	if( !$delivery->save() ){
            if ( $request->hasFile('proof') ) {
               $fileDelete = File::where('path', '=', $file->path)->first();
               Storage::delete($fileDelete->path);
               $fileDelete->delete(); 
            }

            return response()->json([
                'success'   => false,
                'message'   => 'Konfirmasi pengiriman gagal'
            ]);
        }
        else{
            return response()->json([
                'success'  => true,
                'message'  => 'Konfirmasi pengiriman berhasil'
            ]);
        }
    }
}
