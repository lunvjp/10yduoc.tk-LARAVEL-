<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\subject;
use App\unit;
use App\test;
use App\do_test;
use App\do_question;
use App\question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    // Ajax Click Input
    public function doQuestion(Request $request) {
        $do_question = new do_question;
        $do_question->users_id = Auth::id();
        $do_question->questions_id = $request['question_id'];
        $do_question->tests_id = $request['test_id'];
        $do_question->answerofuser = strtoupper($request['answerofuser']);
        $do_question->save();

        return response()->json(['do_question' => $do_question]);
    }

    // Ajax Load Test
    public function doTest(Request $request,$subject_id,$test_id = null) {
        if ($request->ajax()) {
            // Check if user click more than one time into "yes" button
            $onetime = do_test::where([
                ['users_id', Auth::id()],
                ['tests_id',$request['test_id']],
            ])->first();

            if (!$onetime) {
                $do_test = new do_test;
                $do_test->users_id = Auth::id();
                $do_test->tests_id = $request['test_id'];
                $do_test->save();
            }
            //----------------------------------------------------------
            // Load List Question On The Right Side
            $questions = DB::table('questions')
                ->join('manage_tests','questions.id','=','manage_tests.questions_id') // question.id = manage_test.question_id
                ->join('tests','tests.id','=','manage_tests.tests_id') // manage_test.test_id = test.id
                ->select('manage_tests.index','questions.id','questions.name','questions.a','questions.b','questions.c','questions.d','questions.e','questions.f','questions.g','questions.answer')
                ->where('tests.id',$request['test_id'])
                ->whereNotIn('questions.id',function($query){
                    $query->select('do_questions.questions_id')->from('do_questions')->where('users_id',Auth::id());
                })
                ->orderBy('manage_tests.index')
                ->get();

            $content = '';

            if (!empty($questions)) {
                // It means URL have ...{subject_id}/{test_id}... when user click link on left list Test
                if ($test_id) {
                    if ($request['type']) { // Do Not Yet Done Questions
                        $content = '<form action="./'.$request['test_id'].'" method="post" name="form-do-more-question" id="form-do-more-question">
                            <input type="hidden" name="testid">
                            <input type="hidden" name="_token" value='.csrf_token().'>';
                    } else $content = '<form action="./'.$request['test_id'].'" method="post" name="form-do-test" id="form-do-test">
                            <input type="hidden" name="testid">
                            <input type="hidden" name="_token" value='.csrf_token().'>';
                } else
                {
                    $content = '<form action="./'.$subject_id.'/'.$request['test_id'].'" method="post" name="form-do-test" id="form-do-test">
                        <input type="hidden" name="testid">
                        <input type="hidden" name="_token" value='.csrf_token().'>';
                }

                $data = $questions;
                foreach ($data as $key => $value) {
                    $item['A'] = $value->a;
                    $item['B'] = $value->b;
                    if ($value->c) $item['C'] = $value->c;
                    if ($value->d) $item['D'] = $value->d;
                    if ($value->e) $item['E'] = $value->e;
                    if ($value->f) $item['F'] = $value->f;

                    $temp = '<div class="question" id="'.$value->id.'">
                    <div class="item">
                        <p class="title">Câu ' . $value->index . '.</p>
                        <p class="title-content">' . $value->name . '</p>
                    </div>';
                    foreach($item as $i => $val) {
                        $temp .= '<div class="item">
                        <p class="answer">'.$i.'.</p>
                        <p style="width:2%;vertical-align: top;"><input class="'.$value->id.'" value="'.strtolower($i).'" type="radio" name="'.$value->id.'"></p>
                        <p style="padding-left:10px;"><span>'.$val.'</span></p>
                    </div>';
                    }
                    if ($request['type']) {
                        $temp .= '<input class="key" type="hidden" disabled value="'.$value->answer.'">';
                        $temp .= '<p class="insert-answer" style="display:none"></p>';
                    }

                    $temp .='<hr></div>';
                    $content .=$temp;

                }
                $content .='</form>';
            }
            else $content = '<div style="margin: 10px;" class="alert alert-success"><strong>Success!</strong> Đã làm hết đề</div>';

            return response()->json(['questions' => $content,'time' => test::find($request['test_id'])->time]);
        }
        return redirect()->route('UserController.showResults',[$subject_id,$request->testid]);
    }

    // Function Show All Of Done Questions
    private function loadQuestionDone($test_id, $type = null) {
        if ($type) {
            if ($type == 'right') {
                return DB::table('questions')
                    ->join('manage_tests','questions.id','=','manage_tests.questions_id')
                    ->join('tests','manage_tests.tests_id','=','tests.id')
                    ->join('do_questions','questions.id', '=','do_questions.questions_id')
                    ->select('manage_tests.index','questions.id','questions.name','questions.a','questions.b','questions.c','questions.d','questions.e','questions.f','questions.g','do_questions.answerofuser','questions.answer')
                    ->where([
                        ['do_questions.users_id',Auth::id()],
                        ['tests.id',$test_id],
                    ])
                    ->whereColumn('do_questions.answerofuser','questions.answer')
                    ->orderBy('manage_tests.index')
                    ->get();
            }

            return DB::table('questions')
                    ->join('do_questions','do_questions.questions_id','=','questions.id')
                    ->join('manage_tests','manage_tests.questions_id','=','questions.id')
                    ->join('tests','manage_tests.tests_id','=','tests.id')
                    ->select('manage_tests.index','questions.id','questions.name','questions.a','questions.b','questions.c','questions.d','questions.e','questions.f','questions.g','do_questions.answerofuser','questions.answer')
                    ->where([
                        ['do_questions.users_id',Auth::id()],
                        ['tests.id',$test_id],
                    ])
                    ->whereColumn('do_questions.answerofuser','<>','questions.answer')
                    ->orderBy('manage_tests.index')
                    ->get();
        }

        return $detailofdonesentence = DB::table('questions')
            ->join('do_questions','do_questions.questions_id','=','questions.id')
            ->join('manage_tests','manage_tests.questions_id','=','questions.id')
            ->join('tests','manage_tests.tests_id','=','tests.id')
            ->select('manage_tests.index','questions.id','questions.name','questions.a','questions.b','questions.c','questions.d','questions.e','questions.f','questions.g','do_questions.answerofuser','questions.answer')
            ->where([
                ['do_questions.users_id',Auth::id()],
                ['tests.id',$test_id]
            ])
            ->orderBy('manage_tests.index')
            ->get();
    }

    // Show True/False Questions
    public function showQuestions(Request $request) {
        if ($request->has('type')) {
            $questions = $this->loadQuestionDone($request['test_id'],$request['type']);
            $html = '';

            if (!empty($questions)) {
                foreach ($questions as $key => $value) {
                    $check = $value->answer == $value->answerofuser;
                    $html .= '<div class="question '.($check ? "right" : "wrong").'">
                            <div class="item">
                                <p class="title">Câu '.$value->index.'.</p>
                                <p class="title-content">'.$value->name.'</p>
                            </div>';

                    $temp['A'] = $value->a;
                    $temp['B'] = $value->b;
                    if ($value->c) $temp['C'] = $value->c;
                    if ($value->d) $temp['D'] = $value->d;
                    if ($value->e) $temp['E'] = $value->e;
                    if ($value->f) $temp['F'] = $value->f;
                    if ($value->g) $temp['G'] = $value->g;


                    foreach($temp as $key2 => $value2) {
                        $html .= '  <div class="item">
                                    <p class="answer">'.$key2.'.</p>
                                    <p>' . $value2 . '</p>
                                </div>';
                    }

                    $html .= '<p class="insert-answer" style="color: '.($check ? 'blue' : 'red').'">'.$value->answer.' - Trả lời '.$value->answerofuser.'</p>
                            </div>';
                }
            } else {
                $html = '<div style="margin: 10px;" class="alert alert-info"><strong>Thông báo! </strong>Không có bài làm '.($request['type'] == 'right' ? 'đúng' : 'sai').'</div>';
            }

            return response()->json(['questions' => $html]);
        }
    }

    // Show Result Of A Test (default: when user click a test, after submit form do test)
    public function showResults($subject_id,$test_id) {
        $listTest = $this->loadListTest($subject_id);
        $questions = $this->loadQuestionDone($test_id);

        return view('user.dotest.index',['subject_id' => $subject_id,'test_id' => $test_id,'listTest' => $listTest,'questions' => $questions]);
    }

    public function showTests($subject_id)
    {
        $listTest = $this->loadListTest($subject_id);
        return view('user.dotest.index',['subject_id' => $subject_id,'listTest' => $listTest]);
    }

    public function loadListTest($subject_id) {
        $listTest = array();
        $allTest = subject::find($subject_id)->tests;
        foreach ($allTest as $test) {
            $item = array();
            $item['id'] = $test->id;
            $item['name'] = $test->name;
            $checkDoTest = do_test::where([
                ['tests_id',$test->id],
                ['users_id',Auth::id()]
            ])->first();
            $item['all'] = $test->count;
            $item['check'] = $checkDoTest;

            if ($checkDoTest) {
                $item['count'] = do_question::where([
                    ['users_id',Auth::id()],
                    ['tests_id',$test->id]
                ])->count('questions_id');
            }
            array_push($listTest,$item);

        }

        return $listTest; // array
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $subjects = subject::all();
        return view('user.dotest.index',['subjects' => $subjects]);
    }
}