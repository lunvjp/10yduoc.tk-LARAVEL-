<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\subject;
use App\unit;
use App\test;
use App\question;
use App\manage_test;
use App\HomeModel;
use App\tien_question;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function tien() {
        $questions = tien_question::all();

        return view('welcome',['questions' => $questions]);
    }

    public function showSubjects()
    {
        $subjects = subject::all();
        return view('home', ['subjects' => $subjects]);
    }

    public function index() {
        $subjects = subject::all();
        $tests = array();
        foreach ($subjects as $subject) {
            $test = $subject->tests->toArray();
            $tests[$subject->name] = $test;
        }
        return view('home', ['subjects' => $subjects,'tests' => $tests]);
    }

    public function loadListTest()
    {
        return DB::table('tests')
            ->join('units', 'tests.units_id', '=', 'units.id')
            ->join('subjects', 'units.subjects_id', '=', 'subjects.id')
            ->join('manage_tests', 'manage_tests.tests_id', '=', 'tests.id')
            ->where('subjects.id', 1)
            ->select('tests.id', 'tests.name', DB::raw('count(manage_tests.questions_id) as total'))
            ->groupBy('tests.id')
            ->get();
    }

    // Cái này gửi ajax làm cho sướng
    public function loadQuestion($test_id)
    {
        return DB::table('tests')
            ->join('manage_tests', 'tests.id', '=', 'manage_tests.tests_id')
            ->join('questions', 'questions.id', '=', 'manage_tests.questions_id')
            ->where('tests.id', $test_id)
            ->select('questions.id','questions.name as question','questions.a','questions.b','questions.c','questions.d','questions.e','questions.f','questions.answer')
            ->orderBy('manage_tests.index')
            ->get();
    }

    /*
    public function doTest()
    {
        // Ajax load question on the right when user click each line on the left site
        if (isset($_POST['id'])) {
            $content = '<form method="post" name="form-add" id="form-do-test"><input type="hidden" name="done" value="' . $_POST['id'] . '">';
            $id = htmlspecialchars($_POST['id']);
            $id = trim($id);

            $data = $this->model->loadQuestion($id);

            $result = array();
            foreach ($data as $key => $value) {
                if ($key == 20) break;
                $result[$value['id']] = $value['answer'];

                $item['A'] = $value['a'];
                $item['B'] = $value['b'];
                $item['C'] = $value['c'];
                $item['D'] = $value['d'];
                if ($value['e']) $item['E'] = $value['e'];
                if ($value['f']) $item['F'] = $value['f'];


                $temp = '<div class="question" id="' . $value['id'] . '">
                    <div class="item">
                        <p class="title">Câu ' . ($key + 1) . '.</p>
                        <p class="title-content">' . $value['question'] . '</p>
                    </div>';
                foreach ($item as $i => $val) {
                    $temp .= '<div class="item">
                        <p class="answer">' . $i . '.</p>
                        <p style="width:2%;vertical-align: middle;"><input class="' . $value['id'] . '" value="' . strtolower($i) . '" type="radio" name="' . $value['id'] . '"></p>
                        <p style="padding-left:10px;"><span>' . $val . '</span></p>
                    </div>';
                }
                $temp .= '<hr></div>';
                $content .= $temp;

            }
            $content .= '</form>';
            $this->view->content['listQuestion'] = $content;

//          echo $content;
            $_SESSION['answer'] = $result; // mảng kết quả
//          $_SESSION['timeout']=(time() + 30*60) * 1000;

            $this->view->render('load');
        }
    }
    */
}
