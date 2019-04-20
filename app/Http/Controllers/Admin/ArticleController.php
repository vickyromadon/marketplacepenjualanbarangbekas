<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
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
                "title",
                "description",
                "link",
            ];

            $total = Article::where("title", 'LIKE', "%$search%")
                    ->where( function($q) use ($search){
                        $q->where("title", 'LIKE', "%$search%");
                    })
            		->count();

            $data = Article::where("title", 'LIKE', "%$search%")
                    ->where( function($q) use ($search){
                        $q->where("title", 'LIKE', "%$search%");
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

    public function store(Request $request)
    {

    	if( $request->isMethod('post') ){
	    	$validator = $request->validate([
	    		'title'			=> 'required|string',
	    		'description'	=> 'nullable|string',
	    		'link'			=> 'nullable|string',
	    	]);

	    	$article 				= new Article();
	    	$article->title 		= $request->title;
	    	$article->description 	= $request->description;
	    	$article->link 			= $request->link;

	    	if( $article->save() ){
		    	return response()->json([
		            'success'   => true,
		            'message'   => 'Berhasil Menambahkan'
		        ]);
	    	}
	    	else{
	    		return response()->json([
		            'success'   => false,
		            'message'   => 'Gagal Menambahkan'
		        ]);
	    	}		    		
    	}
    	return $this->view();
    }

    public function destroy($id)
    {
        $article = Article::find($id);
        if( $article->delete() ){
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

    public function edit(Request $request){	    
		return $this->view(['data' => Article::find($request->id)]);    
    }
}
