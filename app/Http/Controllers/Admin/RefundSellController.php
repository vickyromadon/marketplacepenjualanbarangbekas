<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Bank;
use App\Models\Refund;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RefundSellController extends Controller
{
    public function index(Request $request)
    {
    	if( $request->isMethod('post') ){
    		$search;
            $status     = $request->status;
            $start      = $request->start;
            $length     = $request->length;

            if( !empty($request->search) )
                $search = $request->search['value'];
            else
                $search = null;

            $column = [
                "user_name",
                "price",
                "status",
                "created_at"
            ];

            $total = DB::table('refunds')->join('users', 'refunds.user_id', '=', 'users.id')
                	->where('refunds.type', Refund::TYPE_SELL)
        			->where( function($q) use ($search, $status) {
                        $q->where("users.name", 'LIKE', "%$search%")
	                    ->where("refunds.status", 'LIKE', "$status%");
                    })
                    ->count();

            $data = DB::table('refunds')->join('users', 'refunds.user_id', '=', 'users.id')
            		->select(
            					'refunds.id AS id',
            					'users.name AS name',
            					'refunds.price AS price',
            					'refunds.status AS status',
            					'refunds.created_at AS created_at'
            		)
                	->where('refunds.type', Refund::TYPE_SELL)
        			->where( function($q) use ($search, $status) {
                        $q->where("users.name", 'LIKE', "%$search%")
	                    ->where("refunds.status", 'LIKE', "$status%");
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

    public function show($id)
    {
        $refund = Refund::find($id);

        return $this->view([
            'refund'      => $refund,
            'banks'     => Bank::where('user_id', $refund->user_id)->get(),
        ]);
    }

    public function finish($id, Request $request){
        $validator = $request->validate([
            'bank'      => 'required',
        ]);

        $refund             = Refund::find($id);
        $refund->bank_id    = $request->bank;
        $refund->status     = Refund::STATUS_FINISH;

        $user       = User::find($refund->user_id);
        $user->poin += intval(intval($refund->price) / 10000);
        $user->save();

        if( $refund->save() ){
            return response()->json([
                'success' => true,
                'message' => 'Perubahan berhasil disimpan',
            ]);
        }
        else{
            return response()->json([
                'success' => true,
                'message' => 'Terjadi Kesalahan, Silahkan Coba Lagi',
            ]);
        }
    }
}
