<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidRequest extends Model
{
    const STATUS_PENDING    = 'pending';
    const STATUS_APPROVE    = 'approve';
    const STATUS_REJECT     = 'reject';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'request_id', 'price', 'processing_time', 'status', 'description'];

    /**
     * Get the user that owns the bidrequest.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the request that owns the bidrequest.
     */
    public function request()
    {
        return $this->belongsTo('App\Models\Request');
    }

    /**
     * Get the request that owns the bidrequest.
     */
    public function req()
    {
        return $this->hasOne('App\Models\Request');
    }
}
