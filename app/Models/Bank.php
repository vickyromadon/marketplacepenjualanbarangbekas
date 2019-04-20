<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'number', 'owner', 'user_id', 'admin_id'];

    /**
     * Get the users for the bank.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the admins for the bank.
     */
    public function admins()
    {
        return $this->hasMany('App\Models\Admin');
    }

    /**
     * Get the transaction for the file.
     */
    public function transaction()
    {
        return $this->hasOne('App\Models\Transaction');
    }
}
