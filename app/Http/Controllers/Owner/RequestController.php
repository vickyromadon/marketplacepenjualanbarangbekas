<?php

namespace App\Http\Controllers\Owner;

use App\Models\File;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\BidRequest;
use Illuminate\Http\Request;
use App\Models\Request as Req;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class RequestController extends Controller
{
    public function index(){
    	return $this->view([
    		'requests' 		=> Req::where('status', Req::STATUS_WAITING)->orderBy('created_at', 'desc')->get(),
    		'categories' 	=> Category::all(),
    	]);
    }

    public function show($id){
    	return $this->view([
    		'request' => Req::find($id),
    	]);
    }

    public function on_progress(){
        return $this->view([
            'bid_requests' => BidRequest::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function detail($id){
        return $this->view([
            'request' => Req::find($id),            
        ]);
    }

    public function confirmation(Request $request){
        $validator = $request->validate([
            'file_id'               => 'required|mimes:jpeg,jpg,png|max:5000',
        ]);

        $req                    = Req::find($request->id);
        
        $product                = new Product();
        $product->name          = $req->title;
        $product->quantity      = $req->quantity;
        $product->price         = $req->price;
        $product->status        = Product::STATUS_PUBLISH;
        $product->type          = Product::TYPE_REQUEST;
        $product->description   = '-';

        $user = User::where('id', '=', Auth::user()->id )->first();
        $product->user()->associate($user);

        $category = Category::where('id', '=', $req->category_id)->first();
        $product->category()->associate($category);

        if( $request->file_id != null ){
            $filename  = $request->file('file_id')->getClientOriginalName();
            $path      = $request->file('file_id')->store('request/' . Auth::user()->id);
            $extension = $request->file('file_id')->getClientOriginalExtension();
            $size      = $request->file('file_id')->getClientSize();

            $file            = new File();
            $file->filename  = time() . '_' . $filename;
            $file->title     = $request->name;
            $file->path      = $path;
            $file->extension = $extension;
            $file->size      = $size;
            $file->save();

            $product->file()->associate($file);
        }

        if( !$product->save() ){
            if ( $request->hasFile('file_id') ) {
               $fileDelete = File::where('path', '=', $file->path)->first();
               Storage::delete($fileDelete->path);
               $fileDelete->delete(); 
            }

            return response()->json([
                'success'   => false,
                'message'   => 'Konfirmasi Gagal'
            ]);
        }
        else{
            $req->product_id   = $product->id;
            $req->status        = Req::STATUS_FINISH;
            $req->save();

            return response()->json([
                'success'  => true,
                'message'  => 'Konfirmasi Berhasil'
            ]);
        }
    }
}
