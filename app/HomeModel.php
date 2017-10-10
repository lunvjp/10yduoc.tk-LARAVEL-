<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\subject;
use App\unit;
use App\test;
use App\question;
use App\manage_test;

class HomeModel extends Model
{
    //
    public function loadListTest() {
        return DB::table('tests')
            ->join('units','tests.units_id','=','units.id')
            ->join('subjects','units.subjects_id','=','subjects.id')
            ->join('manage_tests','manage_tests.tests_id','=','tests.id')
            ->where('subjects.id',1)
            ->select('tests.id','tests.name',DB::raw('count(manage_tests.questions_id) as total'))
            ->groupBy('tests.id')
            ->get();
    }
}
