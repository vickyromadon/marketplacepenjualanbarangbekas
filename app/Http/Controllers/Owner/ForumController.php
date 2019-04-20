<?php

namespace App\Http\Controllers\Owner;

use App\Models\User;
use App\Models\Forum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index(){
    	return $this->view([
    		'forums' => Forum::get(),
    	]);
    }

    public function store(Request $request){
    	$validator = $request->validate([
            'title'          => 'required|string',
            'description'    => 'required|string',
        ]);

    	$user = User::find(Auth::user()->id);

        $forum 				= new Forum();
        $forum->title 		= $request->title;
        $forum->description = $request->description;
        $forum->user_id 	= $user->id;

        if( $forum->save() ){
            return response()->json([
                'success' => true,
                'message' => 'Forum berhasil di kirim',
            ]);
        }
        else{
            return response()->json([
                'success' => true,
                'message' => 'Forum gagal di kirim',
            ]);
        }
    }

    public function show($id){
    	return $this->view([
    		'forum' => Forum::find($id),
    	]);
    }
}
