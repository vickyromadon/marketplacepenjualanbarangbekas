<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['filename', 'title', 'path', 'extension', 'size'];

    /**
     * Get the admins for the file.
     */
    public function admins()
    {
        return $this->hasMany('App\Models\Admin');
    }

    /**
     * Get the users for the file.
     */
    public function users()
    {
        return $this->hasMany('App\Models\User');
    } 

    /**
     * Get the products for the file.
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * Get the transactions for the file.
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    } 

    /**
     * Get the deliveries for the file.
     */
    public function deliveries()
    {
        return $this->hasMany('App\Models\Delivery');
    }

    /**
     * Get the requests for the file.
     */
    public function requests()
    {
        return $this->hasMany('App\Models\Request');
    }
}
