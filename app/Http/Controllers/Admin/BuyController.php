<?php

namespace App\Http\Controllers\Admin;

use App\Models\Buy;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BuyController extends Controller
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
            	"user.name",
            	"category.name",
            	"weight",
                "price",
                "created_at",
                "status",
            ];

            $total = Buy::with(['category', 'user'])
            		->count();

            $data = Buy::with(['category', 'user'])
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
    	return $this->view([
    		'buy' => Buy::find($id),
    	]);
    }

    public function approve($id){
    	$buy = Buy::find($id);
    	$buy->status = Buy::STATUS_APPROVE;
    	$buy->reason = "Barang yang anda jual kami setujui, kami akan menjemput ke alamat yang sudah anda masukkan sebelumnya";
    	
    	if( !$buy->save() ){
    		return response()->json([
	            'success'   => false,
	            'message'   => 'Terjadi kesalahan, silahkan coba lagi'
	        ]);
    	}
    	else{
	    	$category = Category::find($buy->category_id);
	    	$category->quantity += $buy->weight;
	    	$category->save();

    		return response()->json([
	            'success'   => true,
	            'message'   => 'Berhasil Menyetujui pembelian'
	        ]);
    	}
    }

    public function reject(Request $request, $id){
        $buy = Buy::find($id);
        $buy->status = Buy::STATUS_REJECT;
        $buy->reason = $request->note;
        
        if( !$buy->save() ){
            return response()->json([
                'success'   => false,
                'message'   => 'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
        else{
            return response()->json([
                'success'   => true,
                'message'   => 'Berhasil Menolak pembelian'
            ]);
        }
    }
}
