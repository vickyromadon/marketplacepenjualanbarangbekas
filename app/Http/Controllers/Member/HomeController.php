<?php

namespace App\Http\Controllers\Member;

use App\Models\Product;
use App\Models\UsePoin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = date('d-m-Y');

        $usePoin = UsePoin::where('status', UsePoin::STATUS_PUBLISH)->get();

        foreach ($usePoin as $item) {
            if( date('d-m-Y', strtotime('+1 days', strtotime($item->expired_date))) == $date ){
                $item->status = UsePoin::STATUS_EXPIRED;
                $item->save();
            }
        }


        // dd(UsePoin::where('status', UsePoin::STATUS_PUBLISH)->get());

    	$product = Product::with(['file'])
                    ->where('status', Product:: STATUS_PUBLISH)
                    ->where('type', Product::TYPE_SELL)
                    ->orderBy('view', 'desc')
                    ->paginate(6);

        // dd($product);

        return view('welcome')
        		->with('newProduct', UsePoin::where('status', UsePoin::STATUS_PUBLISH)->get())
                ->with('products', $product);
    }
}
