<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'description'];

    /**
     * Get the user that owns the forum.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the forum_users that owns the forum.
     */
    public function forum_users()
    {
        return $this->hasMany('App\Models\ForumUsers');
    }
}
