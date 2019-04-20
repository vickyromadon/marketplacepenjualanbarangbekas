<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
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
                "email",
                "phone",
                "created_at"
            ];

            $total = User::where("privilege", '=', '0')
            		->where( function($q) use ($search){
	            		$q->where("name", 'LIKE', "%$search%")
	            		->orWhere("email", 'LIKE', "%$search%")
	            		->orWhere("phone", 'LIKE', "%$search%")
	            		->orWhere("created_at", 'LIKE', "%$search%");
            		})
            		->count();

            $data = User::where("privilege", '=', '0')
            		->where( function($q) use ($search){
	            		$q->where("name", 'LIKE', "%$search%")
	            		->orWhere("email", 'LIKE', "%$search%")
	            		->orWhere("phone", 'LIKE', "%$search%")
	            		->orWhere("created_at", 'LIKE', "%$search%");
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
        return $this->view(['data' => User::find($id)]);
    }
}
