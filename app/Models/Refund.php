<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
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
    	'price', 'status', 'user_id',
    	'delivery_id', 'bank_id', 'type',
	];

    /**
     * Get the user that owns the Refund.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the delivery that owns the Refund.
     */
    public function delivery()
    {
        return $this->belongsTo('App\Models\Delivery');
    }

    /**
     * Get the bank that owns the Refund.
     */
    public function bank()
    {
        return $this->belongsTo('App\Models\Bank');
    }
}
