<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumUsers extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'forum_id', 'description'];

    /**
     * Get the user that owns the forum_user.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the forum that owns the forum_user.
     */
    public function forum()
    {
        return $this->belongsTo('App\Models\Forum');
    }
}
