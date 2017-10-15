<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class setting extends Model
{
    public function user() {
        return $this->belongsToMany('App\User','users_has_settings','settings_id','users_id')->withTimestamps();
    }
}
