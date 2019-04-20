<?php

namespace App\Http\Controllers\Owner;

use App\Models\BidRequest;
use Illuminate\Http\Request;
use App\Models\Request as Req;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BidRequestController extends Controller
{
    public function bid(Request $request){
    	$validator = $request->validate([
    		'price'			        => 'required|numeric',
    		'processing_time' 		=> 'required|numeric',
            'description'           => 'required|string',
    	]);

    	$req = Req::find($request->id_request);
    	$req->bidder += 1;
    	$req->save();

        $cekBid = BidRequest::where('request_id', $req->id)
                            ->where('user_id', Auth::user()->id)
                            ->count();

        if($cekBid == 0){
        	$bidRequest 					= new BidRequest();
        	$bidRequest->request_id 		= $req->id;
        	$bidRequest->user_id 			= Auth::user()->id;
        	$bidRequest->price 				= $request->price;
        	$bidRequest->processing_time 	= $request->processing_time;
            $bidRequest->description        = $request->description;

        	if( !$bidRequest->save() ){
                return response()->json([
                    'success'   => false,
                    'message'   => 'Gagal Melakukan Penawaran'
                ]);
            }
            else{
                return response()->json([
                    'success'  => true,
                    'message'  => 'Berhasil Melakukan Penawaran'
                ]);
            }
        }
        else{
            return response()->json([
                'success'  => false,
                'message'  => 'Maaf anda sudah melakukan penawaran sebelumnya'
            ]);
        }

    }
}
