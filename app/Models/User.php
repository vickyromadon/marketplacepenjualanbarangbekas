<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 
        'phone', 'age', 'religion', 
        'gender', 'birthdate', 'birthplace', 
        'privilege', 'poin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the file that owns the user.
     */
    public function file()
    {
        return $this->belongsTo('App\Models\File');
    }

    /**
     * Get the products for the user.
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * Get the carts for the user.
     */
    public function carts()
    {
        return $this->hasMany('App\Models\Cart');
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    /**
     * Get the deliveries for the user.
     */
    public function deliveries()
    {
        return $this->hasMany('App\Models\Delivery');
    }

    /**
     * Get the requests for the user.
     */
    public function requests()
    {
        return $this->hasMany('App\Models\Request');
    }

    /**
     * Get the bid_requests for the user.
     */
    public function bid_requests()
    {
        return $this->hasMany('App\Models\BidRequest');
    }

    /**
     * Get the forums for the user.
     */
    public function forums()
    {
        return $this->hasMany('App\Models\Forum');
    }

    /**
     * Get the forum_users that owns the user.
     */
    public function forum_users()
    {
        return $this->hasMany('App\Models\ForumUsers');
    }

    /**
     * Get the buys for the user.
     */
    public function buys()
    {
        return $this->hasMany('App\Models\Buy');
    } 

    /**
     * Get the user_poins for the user.
     */
    public function user_poins()
    {
        return $this->hasMany('App\Models\UserPoin');
    }     
}
