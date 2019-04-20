<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Bank;
use App\Models\Cart;
use App\Models\File;
use App\Models\Refund;
use App\Models\Product;
use App\Models\Delivery;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionRequestController extends Controller
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

            $total = Transaction::where('status', '<>', Transaction::STATUS_NOT_PAYMENT)
                    ->where('type', Transaction::TYPE_REQUEST)
                    ->count();

            $data = Transaction::where('status', '<>', Transaction::STATUS_NOT_PAYMENT)
                    ->where('type', Transaction::TYPE_REQUEST)
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
            'delivery'      => Delivery::where('transaction_id', $transaction->id)->get(),
        ]);
    }

    public function approve($id){
        $transaction            = Transaction::find($id);

        $arr_product 	= $this->toInt($transaction->arr_product);
        $arr_quantity 	= $this->toInt($transaction->arr_quantity);

        $product = Product::whereIn('id', $arr_product)->get();

        for ($i=0; $i < count($product); $i++) { 
        	$product[$i]->quantity -= $arr_quantity[$i];
        	$product[$i]->save();
        }

        $transaction->status    = Transaction::STATUS_APPROVE;
        $transaction->note      = 'Transaksi sudah di setujui, barang akan segera dikirim ke alamat yang sudah diberikan';

        if( !$transaction->save() ){
            return response()->json([
                'success'   => false,
                'message'   => 'Terjadi Kesalahan, Silahkan Coba Lagi'
            ]);
        }
        else{
            for ($i=0; $i < count($product); $i++) { 
                $delivery[$i]                   = new Delivery();
                $delivery[$i]->user_id          = $product[$i]->user->id;
                $delivery[$i]->product_id       = $product[$i]->id;
                $delivery[$i]->transaction_id   = $transaction->id;
                $delivery[$i]->quantity         = $arr_quantity[$i];
                $delivery[$i]->save();
            }
            
            return response()->json([
                'success'  => true,
                'message'  => 'Transaksi Berhasil di Setujui'
            ]);
        }
    }

    public function reject($id, Request $request){
        $validator = $request->validate([
            'note'          => 'required|string|max:191',
        ]);

        $transaction            = Transaction::find($id);
        $transaction->status    = Transaction::STATUS_REJECT;
        $transaction->note      = $request->note;

        if( !$transaction->save() ){
            return response()->json([
                'success'   => false,
                'message'   => 'Terjadi Kesalahan, Silahkan Coba Lagi'
            ]);
        }
        else{
            return response()->json([
                'success'  => true,
                'message'  => 'Transaksi Berhasil di Tolak'
            ]);
        }
    }

    public function finish($id){
        $transaction            = Transaction::find($id);
        $transaction->status    = Transaction::STATUS_FINISH;
        $transaction->note      = 'Barang sudah dikirim, Terima Kasih.';

        if( !$transaction->save() ){
            return response()->json([
                'success'   => false,
                'message'   => 'Terjadi Kesalahan, Silahkan Coba Lagi'
            ]);
        }
        else{
            $delivery = Delivery::where('transaction_id', $transaction->id)->get();

            $transaction = Transaction::find($id);
            
            $arr_price      = $this->toInt($transaction->arr_price);
            $arr_product    = $this->toInt($transaction->arr_product);

            $product = Product::whereIn('id', $arr_product)->get();

            for ($i=0; $i < count($delivery); $i++) { 
                $refund[$i]                 = new Refund();
                $refund[$i]->user_id        = $delivery[$i]->user_id;
                $refund[$i]->delivery_id    = $delivery[$i]->id;
                $refund[$i]->price          = $arr_price[$i];
                $refund[$i]->type           = $product[$i]->type;
                $refund[$i]->save();
            }

            return response()->json([
                'success'  => true,
                'message'  => 'Transaksi Berhasil di Selesaikan'
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
