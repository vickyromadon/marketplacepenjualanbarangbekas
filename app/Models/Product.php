<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const STATUS_PUBLISH        = 'publish';
    const STATUS_UNPUBLISH      = 'unpublish';
    const STATUS_BLOCKIR        = 'blockir';

    const TYPE_SELL     = 'sell';
    const TYPE_REQUEST  = 'request';
    const TYPE_AUCTION  = 'auction';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'categoty_id', 'user_id', 'file_id',
    	'name', 'quantity', 'price',
    	'stock', 'description', 'view',
    	'status', 'note', 'sold',
	];

	/**
     * Get the file that owns the product.
     */
    public function file()
    {
        return $this->belongsTo('App\Models\File');
    }

    /**
     * Get the user that owns the Products.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the sub_category that owns the Products.
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * Get the carts for the product.
     */
    public function carts()
    {
        return $this->hasMany('App\Models\Cart');
    }

    /**
     * Get the deliveries for the product.
     */
    public function deliveries()
    {
        return $this->hasMany('App\Models\Delivery');
    }

    /**
     * Get the requests for the product.
     */
    public function requests()
    {
        return $this->hasMany('App\Models\Request');
    }

    /**
     * Get the use_poins for the product.
     */
    public function use_poins()
    {
        return $this->hasMany('App\Models\UsePoin');
    }    

}
