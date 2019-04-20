<?php

namespace App\Http\Controllers\Member;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index($name){
    	$category 	= Category::where('name', $name)->first();
    	$products 	= Product::where('category_id', $category->id)
                        ->where('status', Product::STATUS_PUBLISH)
                        ->where('type', Product::TYPE_SELL)
    					->orderBy('created_at', 'desc')
    					->paginate(3);

    	return $this->view([
    		'category' 	=> $category,
    		'products' 	=> $products,
    	]);
    }

    public function show($id){
    	$product = Product::find($id);

        $product->view += 1;
        $product->save();

    	return $this->view([
    		'product' => $product,
    	]);
    }
}
