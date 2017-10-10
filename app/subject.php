<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class subject extends Model
{
    public function units() {
        return $this->hasMany('App\unit','subjects_id');
    }

    public function tests() {
        return $this->hasManyThrough('App\test','App\unit','subjects_id','units_id','id');
    }
}
