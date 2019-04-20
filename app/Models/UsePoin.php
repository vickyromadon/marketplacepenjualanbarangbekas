<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsePoin extends Model
{
    const STATUS_PUBLISH    = 'publish';
    const STATUS_EXPIRED    = 'expired';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'product_id', 'expired_date', 'status',    	
	];

	/**
     * Get the product that owns the use_poin.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * Get the user that owns the use_poin.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
