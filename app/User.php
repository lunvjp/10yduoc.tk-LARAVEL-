<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

//class User extends Model
//{
//    protected $fillable = ['name','email','password'];
//
//    protected $hidden = ['password','remember_token'];
//
//    //
//    // belongsToMany('tên của model liên quan','bảng liên kết giữa 2 model','khóa chính của class này','khóa chính của model liên quan');
//
//}


class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tests() {
        return $this->belongsToMany('App\test','do_tests')->withTimestamps();
    }

    public function questions() {
        return $this->belongsToMany('App\question','do_questions')->withTimestamps()->withPivot('check','answerofuser');
    }

    public function settings() {
        return $this->belongsToMany('App\setting','users_has_settings','users_id','settings_id')->withTimestamps();
    }
}

