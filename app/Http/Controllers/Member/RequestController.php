<?php

namespace App\Http\Controllers\Member;

use App\Models\User;
use App\Models\File;
use App\Models\Category;
use App\Models\BidRequest;
use Illuminate\Http\Request;
use App\Models\Request as Req;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RequestController extends Controller
{
    public function index(Request $request){
    	if( $request->isMethod('post') ){
    		$validator = $request->validate([
	    		'category'		=> 'required',
	    		'title'			=> 'required|string',
	    		'description'	=> 'required|string',
	    		'file'   		=> 'required|mimes:jpeg,jpg,png|max:5000',
                'quantity'      => 'required|numeric',
	    	]);

    		$category 	= Category::find($request->category);
    		$user 		= User::find(Auth::user()->id);

	    	$req 				= new Req();
	    	$req->title 		= $request->title;
	    	$req->description 	= $request->description;
            $req->quantity      = $request->quantity;

	    	$req->category()->associate($category);
	    	$req->user()->associate($user);

	    	if( $request->file != null ){
	            $filename  = $request->file('file')->getClientOriginalName();
	            $path      = $request->file('file')->store('request/'. Auth::user()->id);
	            $extension = $request->file('file')->getClientOriginalExtension();
	            $size      = $request->file('file')->getClientSize();
	            
	            $file            = new File();
	            $file->filename  = time() . '_' . $filename;
	            $file->title     = $filename;
	            $file->path      = $path;
	            $file->extension = $extension;
	            $file->size      = $size;
	            $file->save();

	            $req->file()->associate($file);
	        }

	        if( !$req->save() ){
	            if ( $request->hasFile('file') ) {
	               $fileDelete = File::where('path', '=', $file->path)->first();
	               Storage::delete($fileDelete->path);
	               $fileDelete->delete(); 
	            }

	            return response()->json([
	                'success'   => false,
	                'message'   => 'Terjadi kesalahan, Silahkan coba lagi'
	            ]);
	        }
	        else{
	            return response()->json([
	                'success'  => true,
	                'message'  => 'Permintaan berhasil disimpan'
	            ]);
	        }
    	}

    	return $this->view([
    		'categories' 	=> Category::get(),
    		'requests' 		=> Req::where('user_id', Auth::user()->id)->get(),
    	]);
    }

    public function show($id){
    	return $this->view([
    		'request' => Req::find($id),
    	]);
    }

    public function bid(Request $request){
    	$validator = $request->validate([
    		'bid_request'		=> 'required',
    	]);

    	$bidRequest 		= BidRequest::find($request->bid_request);
    	$bidRequest->status = BidRequest::STATUS_APPROVE;
    	$bidRequest->save();

    	$bidReq = BidRequest::where('request_id', $request->id_request)->get();
    	foreach($bidReq as $bid){
    		if( $bid->id != $request->bid_request ){
    			$bid->status = BidRequest::STATUS_REJECT;
    			$bid->save();
    		}
    	}

    	$req                   = Req::find($request->id_request);
    	$req->bid_request_id   = $bidRequest->id;
    	$req->status           = Req::STATUS_ON_PROGRESS;
        $req->price            = $bidRequest->price;

    	if( !$req->save() ){
            return response()->json([
                'success' => true,
                'message' => 'Gagal memilih pengrajin',
            ]);
        }
        else{
            return response()->json([
                'success' => true,
                'message' => 'Berhasil memilih pengrajin',
            ]);
        }
    }
}
