<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    const STATUS_WAITING 	    = 'waiting';
    const STATUS_ON_PROGRESS  	= 'on progress';
    const STATUS_FINISH         = 'finish';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'description', 'status', 'user_id',
    	'file_id', 'category_id', 'product_id',
        'bidder', 'price', 'title', 'bid_request_id',
        'quantity',
	];

	/**
     * Get the user that owns the request.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the file that owns the request.
     */
    public function file()
    {
        return $this->belongsTo('App\Models\File');
    }

    /**
     * Get the category that owns the request.
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * Get the product that owns the request.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * Get the bid_requests for the request.
     */
    public function bid_requests()
    {
        return $this->hasMany('App\Models\BidRequest');
    }

    /**
     * Get the bid_request that owns the request.
     */
    public function bid_request()
    {
        return $this->belongsTo('App\Models\BidRequest');
    }
}
