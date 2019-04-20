<?php

namespace App\Http\Controllers\Member;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
    	return $this->view(['articles' => Article::all()]);
    }

    public function show($id){
        return $this->view(['data' => Article::find($id)]);    
    }
}
