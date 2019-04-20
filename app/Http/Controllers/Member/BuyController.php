<?php

namespace App\Http\Controllers\Member;

use App\Models\Buy;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class BuyController extends Controller
{
    public function index(){
    	$category = Category::where('price_buy', '<>', 0)
    				->where('min_buy', '<>', 0)
    				->get();

    	return $this->view([
    		'categories' => $category,
    	]);
    }

    public function show($id){
    	return $this->view([
    		'buy' => Category::find($id),
    	]);
    }

    public function store(Request $request){
    	$validator = $request->validate([
            'weight'     => 'required|numeric',
            'address'    => 'required|string',
        ]);

    	$user = User::find(Auth::user()->id);
        $category = Category::find($request->id_category);

        $buy 				= new Buy();
        $buy->weight 		= $request->weight;
        $buy->address 		= $request->address;
        $buy->category_id 	= $category->id;
        $buy->price 		= intval($request->weight) * intval($category->price_buy);
        $buy->user_id       = $user->id;
        $buy->reason 		= '-';

        if( $buy->save() ){
            return response()->json([
                'success' => true,
                'message' => 'Menjual '. $category->namme .' berhasil',
            ]);
        }
        else{
            return response()->json([
                'success' => true,
                'message' => 'Menjual '. $category->namme .' Gagal',
            ]);
        }
    }

    public function history(Request $request){
    	if( $request->isMethod('post') ){
            $search;
            $start = $request->start;
            $length = $request->length;

            if( !empty($request->search) )
                $search = $request->search['value'];
            else
                $search = null;

            $column = [
            	"category.name",
            	"weight",
                "price",
                "created_at",
                "status",
            ];

            $total = Buy::with(['category'])
            		->count();

            $data = Buy::with(['category'])
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
}
