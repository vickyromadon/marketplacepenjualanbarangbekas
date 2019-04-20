<?php

namespace App\Http\Controllers\Owner;

use App\Models\User;
use App\Models\Forum;
use App\Models\ForumUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ForumUserController extends Controller
{
    public function store(Request $request){
    	$validator = $request->validate([
            'description'    => 'required|string',
        ]);

    	$forum = Forum::find($request->id_forum);
    	$user = User::find(Auth::user()->id);

        $forum_user = new ForumUsers();
        $forum_user->description 	= $request->description;
        $forum_user->user_id 		= $user->id;
        $forum_user->forum_id 		= $forum->id;

        if( $forum_user->save() ){
	    	return response()->json([
	            'success'	=> true,
	            'message'	=> 'Berhasil Kirim'
	        ]);
    	}
    	else{
    		return response()->json([
	            'success'	=> false,
	            'message'	=> 'Gagal Kirim'
	        ]);	
    	}
    }
}
