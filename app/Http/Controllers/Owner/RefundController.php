<?php

namespace App\Http\Controllers\Owner;

use App\Models\User;
use App\Models\Bank;
use App\Models\Refund;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    public function index(Request $request)
    {
    	if( $request->isMethod('post') ){
    		$search;
            $status     = $request->status;
            $type 		= $request->type;
            $start      = $request->start;
            $length     = $request->length;

            if( !empty($request->search) )
                $search = $request->search['value'];
            else
                $search = null;

            $column = [
                "price",
                "status",
                "type",
            ];

            $total = DB::table('refunds')->join('deliveries', 'refunds.delivery_id', '=', 'deliveries.id')
            		->join('products', 'deliveries.product_id', '=', 'products.id')
            		->where('refunds.user_id', Auth::user()->id)
        			->where( function($q) use ($search, $status, $type) {
                        $q->where("refunds.status", 'LIKE', "$status%")
                		->where('refunds.type', 'LIKE', "$type%");
                    })
                    ->count();

            $data = DB::table('refunds')->join('deliveries', 'refunds.delivery_id', '=', 'deliveries.id')
            		->join('products', 'deliveries.product_id', '=', 'products.id')
            		->select(
            					'refunds.id AS id',
            					'refunds.price AS price',
            					'refunds.status AS status',
            					'refunds.type AS type',
            					'deliveries.number_proof AS number_proof',
            					'deliveries.quantity AS quantity',
            					'products.name AS product_name'
            		)
            		->where('refunds.user_id', Auth::user()->id)
        			->where( function($q) use ($search, $status, $type) {
                        $q->where("refunds.status", 'LIKE', "$status%")
                		->where('refunds.type', 'LIKE', "$type%");
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

    	return $this->view();
    }
}
