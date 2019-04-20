<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'price_sell', 'price_buy', 'quantity', 'mini_sell', 'mini_buy'];

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * Get the requests for the category.
     */
    public function requests()
    {
        return $this->hasMany('App\Models\Request');
    }

    /**
     * Get the buys for the category.
     */
    public function buys()
    {
        return $this->hasMany('App\Models\Buy');
    }
}
