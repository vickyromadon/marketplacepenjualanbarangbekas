<?php

namespace App\Http\Controllers\Member;

use App\Models\User;
use App\Models\Bank;
use App\Models\Cart;
use App\Models\File;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
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
                "total_price",
                "created_at",
                "status",
            ];

            $total = Transaction::where('user_id', Auth::user()->id)
            			->count();

            $data = Transaction::where('user_id', Auth::user()->id)
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

    public function store(Request $request){
    	$user = User::find(Auth::user()->id);

    	$transaction 				= new Transaction();
    	$transaction->arr_product 	= $request->arr_product;
    	$transaction->arr_price 	= $request->arr_price;
    	$transaction->arr_quantity 	= $request->arr_quantity;
    	$transaction->total_price 	= $request->total_price;
    	$transaction->user_id 		= $user->id;
        $transaction->type          = $request->type;

    	if(!$transaction->save()){
    		return response()->json([
	        	'success'   => false,
	            'message'   => 'Terjadi Kesalahan, Silahkan Coba Lagi',
        	]);
        }
        else{
        	$cart = Cart::where('user_id', Auth::user()->id)->get();
        	foreach ($cart as $item) {
        		$item->delete();
        	}

        	return response()->json([
	        	'success'   => true,
	            'message'   => 'Berhasil silahkan konfirmasi pembayaran',	
	        ]);
        }
    }

    public function show($id)
    {
    	$transaction = Transaction::find($id);

    	$arr_product 	= $this->toInt($transaction->arr_product);
    	$arr_price 		= $this->toInt($transaction->arr_price);
    	$arr_quantity 	= $this->toInt($transaction->arr_quantity);

    	$product = Product::whereIn('id', $arr_product)->get();

    	$arr_result = [];
    	for ($i=0; $i < count($product) ; $i++) { 
    		$arr_result[$i] = array(
    							$product[$i],
    							$arr_price[$i],
    							$arr_quantity[$i],
    						);
    	}

        return $this->view([
        	'transaction' 	=> $transaction,
        	'data' 			=> $arr_result,
        	'banks' 		=> Bank::where('admin_id', '<>', null)->get(),
        ]);
    }

    public function update(Request $request){
    	$validator = $request->validate([
            'name'          => 'required|string|max:191',
            'phone'         => 'required|string|phone:ID',
            'address'       => 'required|string|max:191',
            'bank'          => 'required|numeric',
            'transfer_date' => 'required|date',
            'proof'  	 	=> 'required|mimes:jpeg,jpg,png|max:5000',
        ]);

    	$bank = Bank::find($request->bank);

        $transaction 				= Transaction::find($request->id);
        $transaction->name          = $request->name;
        $transaction->phone         = $request->phone;
        $transaction->address 		= $request->address;
        $transaction->bank_id 		= $bank->id;
        $transaction->transfer_date = $request->transfer_date;
        $transaction->status 		= Transaction::STATUS_PENDING;

        if( $request->proof != null ){
    		$filename  = $request->file('proof')->getClientOriginalName();
	        $path      = $request->file('proof')->store('proof_transaction/' . Auth::user()->id);
	        $extension = $request->file('proof')->getClientOriginalExtension();
	        $size      = $request->file('proof')->getClientSize();

	        $file            = new File();
	        $file->filename  = time() . '_' . $filename;
	        $file->title     = $filename;
	        $file->path      = $path;
	        $file->extension = $extension;
	        $file->size      = $size;
	        $file->save();

	        $transaction->file()->associate($file);
    	}

    	if( !$transaction->save() ){
            if ( $request->hasFile('proof') ) {
               $fileDelete = File::where('path', '=', $file->path)->first();
               Storage::delete($fileDelete->path);
               $fileDelete->delete(); 
            }

            return response()->json([
                'success'   => false,
                'message'   => 'Konfirmasi pembayaran gagal'
            ]);
        }
        else{
            return response()->json([
                'success'  => true,
                'message'  => 'Konfirmasi pembayaran berhasil'
            ]);
        }
    }

    public function cancel($id){
    	$transaction 			= Transaction::find($id);
    	$transaction->status 	= Transaction::STATUS_CANCEL;
    	$transaction->note 		= 'Transaksi telah di batalkan';

    	if( !$transaction->save() ){
            return response()->json([
                'success'   => false,
                'message'   => 'Terjadi Kesalahan, Silahkan Coba Lagi'
            ]);
        }
        else{
            return response()->json([
                'success'  => true,
                'message'  => 'Transaksi Berhasil di Batalkan'
            ]);
        }
    }

    private function toInt(Array $arr){
    	$arr_result = [];

    	for ($i=0; $i < count($arr); $i++) { 
    		$arr_result[$i] = intval($arr[$i]);
    	}

    	return $arr_result;
    }
}
