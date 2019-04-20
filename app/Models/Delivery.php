<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    const STATUS_PENDING    = 'pending';
    const STATUS_DELIVERY   = 'delivered';
    const STATUS_ARRIVE     = 'arrive';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'product_id', 'transaction_id', 'file_id', 'user_id',
    	'delivery_at', 'arrive_at', 'number_proof',
    	'status',
	];

	/**
     * Get the file that owns the delivery.
     */
    public function file()
    {
        return $this->belongsTo('App\Models\File');
    }

    /**
     * Get the product for the delivery.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * Get the transaction for the delivery.
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction');
    }

    /**
     * Get the user for the delivery.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
