<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    //
    public function unit() {
        return $this->belongsTo('App\unit');
    }

    public function tests() {
        return $this->belongsToMany('App\test','manage_tests')->withTimestamps()->withPivot('index');
    }

    public function users() {
        return $this->belongsToMany('App\user','do_questions')->withTimestamps()->withPivot('check','answerofuser');
    }
}