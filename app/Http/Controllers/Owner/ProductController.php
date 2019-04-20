<?php

namespace App\Http\Controllers\Owner;

use App\Models\User;
use App\Models\File;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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
                "quantity",
                "view",
                "created_at",
            ];

            $total = Product::with(['file'])
            		->where("user_id", '=', Auth::user()->id )
                    ->where( function($q) use ($search) {
                        $q->where("name", 'LIKE', "%$search%")
                        ->orWhere("quantity", 'LIKE', "%$search%")
                        ->orWhere("view", 'LIKE', "%$search%");
                    })
            		->count();

            $data = Product::with(['file'])
            		->where("user_id", '=', Auth::user()->id )
                    ->where( function($q) use ($search) {
                        $q->where("name", 'LIKE', "%$search%")
                        ->orWhere("quantity", 'LIKE', "%$search%")
                        ->orWhere("view", 'LIKE', "%$search%");
                    })
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
    		'data' => Category::all(),
    	]);
    }

    public function store(Request $request)
    {
		$validator = $request->validate([
    		'name'			        => 'required|string|max:191',
    		'category' 		        => 'required|numeric',
    		'quantity' 		        => 'required|numeric',
    		'price' 		        => 'required|numeric',
    		'file_id'               => 'required|mimes:jpeg,jpg,png|max:5000',
    		'description' 	        => 'required|string',
    		'status'                => 'required',
    	]);

    	$product 				= new Product();
    	$product->name 			= $request->name;
    	$product->quantity 		= $request->quantity;
    	$product->price 		= $request->price;
    	$product->description 	= $request->description;
        $product->status       	= $request->status;	
        $product->type          = Product::TYPE_SELL;	

        $user = User::where('id', '=', Auth::user()->id )->first();
        $product->user()->associate($user);

        $category = Category::where('id', '=', $request->category)->first();
        $product->category()->associate($category);

    	if( $request->file_id != null ){
    		$filename  = $request->file('file_id')->getClientOriginalName();
	        $path      = $request->file('file_id')->store('product/' . Auth::user()->id);
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
                'message'   => 'Gagal Menambahkan'
            ]);
        }
        else{
            return response()->json([
                'success'  => true,
                'message'  => 'Berhasil Menambahkan'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'name'                  => 'required|string|max:191',
            'category'              => 'required|numeric',
            'quantity'              => 'required|numeric',
            'price'                 => 'required|numeric',
            'file_id'               => 'nullable|mimes:jpeg,jpg,png|max:5000',
            'description'           => 'required|string',
            'status'                => 'required',
        ]);

        $product                        = Product::find($request->id);
        $product->name                  = $request->name;
        $product->quantity              = $request->quantity;
        $product->price                 = $request->price;
        $product->description           = $request->description;
        $product->status                = $request->status;

        $category = Category::where('id', '=', $request->category)->first();
        $product->category()->associate($category);

        if( $request->file_id != null ){
            if( $product->file_id != null ){
                $picture = File::find(intval($product->file_id));
                Storage::delete($picture->path);
                $picture->delete();
            }

            $filename  = $request->file('file_id')->getClientOriginalName();
            $path      = $request->file('file_id')->store('product/' . Auth::user()->id . '/');
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

        $product->save();

        if( !$product->save() ){
            if ( $request->hasFile('file_id') ) {
               $fileDelete = File::where('path', '=', $file->path)->first();
               Storage::delete($fileDelete->path);
               $fileDelete->delete(); 
            }

            return response()->json([
                'success'   => false,
                'message'   => 'Gagal Merubah'
            ]);
        }
        else{
            return response()->json([
                'success'  => true,
                'message'  => 'Berhasil Merubah'
            ]);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        
        $file = File::find($product->file_id);
        if( $product->file_id != null ){
            Storage::delete($file->path);
            $file->delete();
        }

        if( $product->delete() ){
            return response()->json([
                'success'   => true,
                'message'   => 'Berhasil Menghapus'
            ]);
        }
        else{
            return response()->json([
                'success'   => false,
                'message'   => 'Gagal Menghapus'
            ]); 
        }
    }

    public function show($id)
    {
        return $this->view(['data' => Product::find($id)]);
    }
}
