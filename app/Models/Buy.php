<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buy extends Model
{
    const STATUS_PENDING    = 'pending';
    const STATUS_APPROVE    = 'approve';
    const STATUS_REJECT     = 'reject';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id', 'category_id', 'price', 
    	'weight', 'status', 'address', 'reason',
	];

	/**
     * Get the user for the buy.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the category for the buy.
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
