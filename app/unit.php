<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class unit extends Model
{
    //
    public function subject() {
        return $this->belongsTo('App\subject','subjects_id');
    }

    public function tests () {
        return $this->hasMany('App\test','units_id');
    }

    public function questions() {
        return $this->hasMany('App\question','units_id');
    }

}
