<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    /**
     * Get the user for the cart.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the product for the cart.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    static function countCart($id)
    {
        return self::where('user_id', $id)->count();
    }
}
