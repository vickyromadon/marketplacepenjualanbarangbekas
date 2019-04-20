<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const STATUS_NOT_PAYMENT    = 'not_payment';
    const STATUS_CANCEL         = 'cancel';
    const STATUS_APPROVE        = 'approve';
    const STATUS_REJECT         = 'reject';
    const STATUS_PENDING        = 'pending';
    const STATUS_FINISH         = 'finish';

    const TYPE_SELL     = 'sell';
    const TYPE_REQUEST  = 'request';
    const TYPE_AUCTION  = 'auction';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'arr_product', 'arr_quantity', 'arr_price',
    	'total_price', 'note', 'transfer_date',
    	'status', 'file_id', 'bank_id', 'address',
        'name', 'phone', 'type',
	];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'arr_product' => 'array',
        'arr_quantity' => 'array',
        'arr_price' => 'array',
    ];

    /**
     * Get the bank for the transaction.
     */
    public function bank()
    {
        return $this->belongsTo('App\Models\Bank');
    }

    /**
     * Get the file for the transaction.
     */
    public function file()
    {
        return $this->belongsTo('App\Models\File');
    }

    /**
     * Get the user for the transaction.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the deliveries for the transaction.
     */
    public function deliveries()
    {
        return $this->hasMany('App\Models\Delivery');
    }
}
