<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class test extends Model
{
    //
    public function unit() {
        return $this->belongsTo('App\unit','units_id');
    }

    // belongsToMany('tên của model liên quan','bảng liên kết giữa 2 model','khóa chính của class này','khóa chính của model liên quan');
    public function users() {
        return $this->belongsToMany('App\user','do_tests','tests_id','users_id')->withTimestamps();
    }

    public function questions() {
        return $this->belongsToMany('App\question','manage_tests','tests_id','questions_id')->withTimestamps()->withPivot('index')->orderBy('index');
    }

    public function getQuestionCount() {
        return count($this->questions);
    }
}