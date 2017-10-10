<?php

namespace App\Http\Controllers;
?>
    <meta charset="utf-8">
<?php
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

use App\Http\Requests;
use App\subject;
use App\unit;
use App\test;
use App\question;
use App\manage_test;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
class ManageController extends Controller
{
    // Dùng middleware để kiểm tra ở đây
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        //
        return view('admin.index');
    }

    public function createSubject(Request $request)
    {
        $this->validate($request, array(
            'newSubject' => 'required|max:255'
        ));
        // store the data
        $subject = new subject;
        $subject->name = $request->newSubject;

        $subject->save();
        return redirect('admin/manageQuestion/' . $subject->id);
    }

    public function createUnit(Request $request, $subject_id)
    {
        $this->validate($request, array(
            'newname' => 'required|max:255'
        ));

        $unit = new unit;
        $unit->name = $request->newname;
        $unit->subjects_id = $subject_id;
        $unit->save();
        return redirect('/admin/manageQuestion/' . $subject_id . '/' . $unit->id);
    }

    public function createTest(Request $request, $subject_id, $unit_id)
    {
        $this->validate($request, array(
            'name' => 'required|max:255|unique:tests,name',
            'time' => 'required',
            'question' => 'required', //
            'a' => 'sometimes|required', // chỉ kiểm tra khi nó tồn tại: sometimes
            'b' => 'sometimes|required',
            'c' => 'sometimes|required',
            'd' => 'sometimes|required',
            'e' => 'sometimes|required',
            'f' => 'sometimes|required',
            'g' => 'sometimes|required',
        ));

        // Nếu type=1 thì tạo mới
        $test = new test;
        $test->name = $request->name;
        $test->time = $request->time;
        $test->units_id = $unit_id;

        $test->save();

        // Nếu type=2 thì


        // Vừa tạo xong đề là tạo câu hỏi luôn
        $this->createQuestions($request,$unit_id,$test->id);
        return redirect('/admin/manageQuestion/' . $subject_id . '/' . $unit_id.'/'.$test->id);
    }

    /*
     * $typeManageTest == 1: Dùng để tạo mới một bảng manage_tests
     * $typeManageTest == 1: Dùng để thêm câu hỏi cho bảng
     */
    private function removeEmptyLines($string)
    {
        return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string);
    }

    public function createQuestions (Request $request, $unit_id,$test_id, $typeManageTest = 1) {
        if ($request->has('remember')) {
            $string = $request->question;
            $string = $this->removeEmptyLines($string);

            preg_match_all('/(?:\d+[\s]*[\.\:\*]+?)[\s]*(.*(?:\n[^[0-9]]*.*)*)/',$string,$matches);

            if (!empty($matches)) {
                $maxIndex = manage_test::where('tests_id', $test_id)->max('index');
                $existsQuestions = array();
                foreach($matches[1] as $key => $value) {
                    // Một value là một tên câu hỏi có trong đó
                    $question = new question;
                    $question->name = $value;

                    if ($request->has('a')) {
                        $a = htmlspecialchars(trim($request->a));
                        if ($a) $question->a = $a;
                    }

                    if ($request->has('b')) {
                        $b = htmlspecialchars(trim($request->b));
                        if ($b) $question->b = $b;
                    }

                    if ($request->has('c')) {
                        $c = htmlspecialchars(trim($request->c));
                        if ($c) $question->c = $c;
                    }

                    if ($request->has('d')) {
                        $d = htmlspecialchars(trim($request->d));
                        if ($d) $question->d = $d;
                    }

                    if ($request->has('e')) {
                        $e = htmlspecialchars(trim($request->e));
                        if ($e) $question->e = $e;
                    }

                    if ($request->has('f')) {
                        $f = htmlspecialchars(trim($request->f));
                        if ($f) $question->f = $f;
                    }

                    if ($request->has('g')) {
                        $g = htmlspecialchars(trim($request->g));
                        if ($g) $question->g = $g;
                    }

                    $question->units_id = $unit_id;

                    $this->checkQuestion($question,$test_id,$maxIndex,$key,$existsQuestions);
                }
                if (!empty($existsQuestions)) {
                    $request->session()->flash('existSentences', $existsQuestions);
                }

            }

//            echo '<pre>';print_r($matches);echo '</pre>';

        } else {
            $pattern = array(
                //-------------------------------------------------------------
                // Có dấu chấm sau đáp án //
                // câu 1. ... 1. ... 2. ... 3. ... 4. ...
                'c.u[\s]*[0-9]*[\s]*[\:\.\*]+?(.*)[\s]*1\.[\s]*(.*)[\s]*2\.[\s]*(.*)[\s]*3\.[\s]*(.*)[\s]*4\.[\s]*(.*)[\s]*',
                // câu 1. ... a. ... b. ... c. ... d. ... e. ...
                'c.u[\s]*[0-9]*[\s]*[\:\.\*]+?(.*)[\s]*a\.[\s]*(.*)[\s]*b\.[\s]*(.*)[\s]*c\.[\s]*(.*)[\s]*d\.[\s]*(.*)[\s]*e\.[\s]*(.*)[\s]*',
                // câu 1. ... a. ... b. ... c. ... d. ...
                'c.u[\s]*[0-9]*[\s]*[\:\.\*]+?(.*)[\s]*a\.[\s]*(.*)[\s]*b\.[\s]*(.*)[\s]*c\.[\s]*(.*)[\s]*d\.[\s]*(.*)[\s]*',
                // 1. ... a. ... b. ... c. ... d. ... e. ...
                '[0-9]*[\s]*[\:\.\*]+?[\s]*(.*)[\s]*a\.[\s]*(.*)[\s]*b\.[\s]*(.*)[\s]*c\.[\s]*(.*)[\s]*d\.[\s]*(.*)[\s]*e\.[\s]*(.*)[\s]*',
                // 1. ... a. ... b. ... c. ... d. ...
                '[0-9]*[\s]*[\:\.\*]+?[\s]*(.*)[\s]*a\.[\s]*(.*)[\s]*b\.[\s]*(.*)[\s]*c\.[\s]*(.*)[\s]*d\.[\s]*(.*)[\s]*',
                // 1. ... a. ... b. ... c. ...
                '[0-9]*[\s]*[\:\.\*]+?[\s]*(.*)[\s]*a\.[\s]*(.*)[\s]*b\.[\s]*(.*)[\s]*c\.[\s]*(.*)[\s]*',
                //-------------------------------------------------------------
                // Không có dấu chấm sau số câu
                // 1 ... (A) ... (B) ... (C) ...
                '[0-9]*[\s]*(.*)[\s]*\(A\)[\s]+(.*)[\s]*\(B\)[\s]+(.*)[\s]*\(C\)[\s]+(.*)[\s]*',
                //-------------------------------------------------------------
                // Không có dấu chấm sau đáp án //
                // 1. ... (A) ... (B) ... (C) ... (D) ...
                '[0-9]*[\s]*[\:\.\*]+?[\s]*(.*)[\s]*\(A\)[\s]*(.*)[\s]*\(B\)[\s]*(.*)[\s]*\(C\)[\s]*(.*)[\s]*\(D\)[\s]*(.*)[\s]*',
                // 1. ... (A) ... (B) ... (C) ...
                '[0-9]*[\s]*[\:\.\*]+?[\s]*(.*)[\s]*\(A\)[\s]+(.*)[\s]*\(B\)[\s]+(.*)[\s]*\(C\)[\s]+(.*)[\s]*',

            );
            $patternArray = $pattern;

            $pattern = implode('|', $pattern);
            $pattern = '#^(' . $pattern . ')$#imuU';
            $data = $this->removeEmptyLines($request->question);

            preg_match_all($pattern, $data, $matches);

            $questions = $matches[0];
//            echo '<pre>';print_r($questions);echo '</pre>';
//            die();
            $existsQuestions = array();
            if (!empty($questions)) {
                $maxIndex = manage_test::where('tests_id', $test_id)->max('index');
                foreach ($questions as $key => $value) {
                    foreach ($patternArray as $pattern) {
                        $pt = "#$pattern#i";
                        preg_match($pt, $value, $sentence);

                        if (!empty($sentence)) {
                            array_shift($sentence);

                            $question = new question;
                            $question->name = $sentence[0];

                            $question->a = $sentence[1];
                            $question->b = $sentence[2];
                            if (count($sentence) >=4) $question->c = $sentence[3];
                            if (count($sentence) >= 5) $question->d = $sentence[4];
                            if (count($sentence) >= 6) $question->e = $sentence[5];
                            if (count($sentence) >= 7) $question->f = $sentence[6];
                            if (count($sentence) >= 8) $question->g = $sentence[7];

                            $question->units_id = $unit_id;

                            $this->checkQuestion($question,$test_id,$maxIndex,$key,$existsQuestions);
                            break;
                        }
                    }
                }
                if (!empty($existsQuestions)) {
                    $request->session()->flash('existSentences', $existsQuestions);
                }
            }
        }

        //-----------------------------------------------------------------
        // Update the count of a test when adding or pushing new question
        $count = manage_test::where('tests_id',$test_id)->count('questions_id');
        test::where('id',$test_id)->update(['count' => $count]);
    }

    private function checkQuestion(question $question, $test_id, $maxIndex,$key, &$existsQuestions) {
        $temp = question::where([
            ['name', $question->name],
            ['a',$question->a],
            ['b',$question->b],
            ['c',$question->c],
            ['d',$question->d],
            ['e',$question->e],
            ['f',$question->f],
            ['g',$question->g],
            ['units_id', $question->units_id],
        ])->first();

        $check = false;
        if ($temp) {
            $tempManageTest = manage_test::where([
                ['tests_id', $test_id],
                ['questions_id', $temp->id]
            ])->first();
            if ($tempManageTest) {
                $existsQuestions[] = $temp->name;
                return;
            } else { // == NULL
                $check = true;
            }
        } else { // Không có câu hỏi
            $question->save(); // Tạo câu hỏi
        }

        $manage_test = new manage_test;
        $manage_test->tests_id = $test_id;

        if ($check) $manage_test->questions_id = $temp->id;
        else $manage_test->questions_id = $question->id;

        if ($maxIndex) {
            $manage_test->index = $maxIndex + $key + 1;
        } else {
            $manage_test->index = $key + 1;
        }

        $manage_test->save();
    }

    public function createMoreQuestions(Request $request,$subject_id,$unit_id,$test_id) {
        $this->validate($request, array(
            'question' => 'required',
            'a' => 'sometimes|required',
            'b' => 'sometimes|required',
            'c' => 'sometimes|required',
            'd' => 'sometimes|required',
            'e' => 'sometimes|required',
            'f' => 'sometimes|required',
            'g' => 'sometimes|required',
        ));

        $this->createQuestions($request,$unit_id,$test_id);

        return redirect('/admin/manageQuestion/' . $subject_id . '/' . $unit_id.'/'.$test_id);

    }

    // Tạo 1 môn mới ở đây
    public function store(Request $request)
    {

    }

    public function showSubjects()
    {
        $subjects = subject::all();
        return view('admin.manageQuestion.index', ['subjects' => $subjects]);
    }

    public function showUnits($subject_id)
    {
        $units = subject::find($subject_id)->units;
        return view('admin.manageQuestion.subject', ['subject_id' => $subject_id, 'units' => $units]);
    }

    public function showTests($subject_id, $unit_id)
    {
        $tests = unit::find($unit_id)->tests;
        return view('admin.manageQuestion.unit', ['subject_id' => $subject_id, 'unit_id' => $unit_id, 'tests' => $tests]);
    }

    public function showQuestions($subject_id, $unit_id, $test_id) {
        $tests = unit::find($unit_id)->tests;
//        $test = question::where()
        $questions = test::find($test_id)->questions;
        return view('admin.manageQuestion.unit', ['subject_id' => $subject_id, 'unit_id' => $unit_id, 'test_id' => $test_id, 'tests' => $tests, 'questions' => $questions]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function updateQuestions(Request $request,$subject_id, $unit_id,$test_id ) {
        // Sắp xếp câu hỏi
        if ( $request->has('index')) {
//            $this->validate($request, array(
//                'answer.*' => 'required',
//            ));

            $data = $request->toArray();
            $indexoftest = $data['index'];
            $check = false;
//            var_dump(question::find(357));
//            echo '<hr>';
//            var_dump(question::where('id',357));
//            die();
            // Cập nhật đáp án
            foreach($indexoftest as $key => $value) {
                $answer = htmlspecialchars(ucfirst(trim($data['answer'][$key])));
                if ($answer) question::where('id',$value)->update(['answer' => $answer]);
            }

            // Lấy ra những câu cần cập nhật
            if ($request->has('checkDelete')) { // Nếu bị xóa thì xóa 1 dòng trong manage_test
                $delete = manage_test::where('tests_id',$test_id)->whereIn('questions_id',$data['checkDelete'])->delete();
                if ($delete>0) $check=true;
                $indexoftest = array_values(array_diff ($data['index'], $data['checkDelete']));
            }

            // Cập nhật chỉ số
            foreach($indexoftest as $key => $value) { // $value = id
                $temp1= manage_test::where(['tests_id' => $test_id, 'questions_id' => $value])->update(['index'=>$key+1]);
                if ($temp1 > 0) $check=true;
            }

            if ($check) Session::flash('success-update','Cập nhật thành công');
        } else {
            $this->validate($request, array(
                'id' => 'required|unique:questions',
                'name.*' => 'required',
                'a.*' => 'required',
                'b.*' => 'required',
                'c.*' => 'sometimes|required',
                'd.*' => 'sometimes|required',
                'e.*' => 'sometimes|required',
                'f.*' => 'sometimes|required',
                'g.*' => 'sometimes|required',
                'answer.*' => 'required',
                'detailofanswer.*' => 'sometimes|required',
            ));

            $data = $request->toArray();

            $check = false;
            foreach($data['id'] as $key => $value) {

                $question = question::find($value);
                // Không cập nhật lại id
                $name = htmlspecialchars(ucfirst(trim($data['name'][$key])));
                $a = htmlspecialchars(ucfirst(trim($data['a'][$key])));
                $b = htmlspecialchars(ucfirst(trim($data['b'][$key])));

                $c = isset($data['c'][$key]) ? htmlspecialchars(ucfirst(trim($data['c'][$key]))) : null;
                $d = isset($data['d'][$key]) ? htmlspecialchars(ucfirst(trim($data['d'][$key]))) : null;
                $e = isset($data['e'][$key]) ? htmlspecialchars(ucfirst(trim($data['e'][$key]))) : null;
                $f = isset($data['f'][$key]) ? htmlspecialchars(ucfirst(trim($data['f'][$key]))) : null;
                $g = isset($data['g'][$key]) ? htmlspecialchars(ucfirst(trim($data['g'][$key]))) : null;
                $answer = htmlspecialchars(ucfirst(trim($data['answer'][$key])));
                $detail_answer = htmlspecialchars(ucfirst(trim($data['detailofanswer'][$key])));

                if ($name) $question->name = $name;
                if ($a) $question->a = $a;
                if ($b) $question->b = $b;
                if ($c) $question->c = $c;
                if ($d) $question->d = $d;
                if ($e) $question->e = $e;
                if ($f) $question->f = $f;
                if ($g) $question->g = $g;
                if ($answer) $question->answer = $answer;
                if ($detail_answer) $question->detail_answer = $detail_answer;

                $saved = $question->save();
                if ($saved) { // Cập nhật thành công
                    $check = true;
                }
            }

            if ($check) {
                Session::flash('success-update','Cập nhật thành công');
            }
        }

        // Vừa tạo xong đề là tạo câu hỏi luôn
        return redirect('/admin/manageQuestion/' . $subject_id . '/' . $unit_id.'/'.$test_id);
    }

    public function destroy($id)
    {
        //
    }

    public function destroyTest($subject_id, $unit_id, $test_id) {
        $manage_test = manage_test::where('tests_id',$test_id)->delete();

        $name = test::find($test_id);
        $test = $name->delete();
        if ($manage_test||$test) Session::flash('success-delete','Xóa đề '.$name.' thành công');

        return redirect('/admin/manageQuestion/' . $subject_id . '/' . $unit_id);
    }
}

/*
 * Vấn đề:
 * 1. Khi thêm 1 câu hỏi, tồn tại câu hỏi trong bảng questions (thì lấy id đã có bỏ vào cho cột questions_id của manage_tests như bình thường)
 * 2. Khi thêm 1 câu hỏi, câu hỏi đó vừa tồn tại trong bảng questions, question_id vừa tồn tại trong bảng manage_tests
 * 2.1: Có trong bảng manage_tests nhưng mà của đề khác (Vẫn tạo mới dữ liệu vào manage_tests như bt)
 * 2.2: Có trong bảng manage_tests của chính đề này (Không lưu hay tạo bất cứ 1 cái gì nữa vì đã trùng rồi, dùng session flash để thông báo)
 */
/*
 * - Chưa có câu hỏi -> Tức là cũng chưa có  trong bảng manage_test nên chỉ
 * -> Tạo question -> Tạo manage_test
 * - Có câu hỏi
 * + Có thể có trong bảng manage_tests (đã ở trong 1 đề nào đó),
 * + Không có trong bảng manage_tests (bị xóa 1 đề và để lại câu hỏi)
 * -> Không tạo câu hỏi
 * -> Kiểm tra manage_test -> Nếu ko trùng tests_id -> Tạo manage_test
 * -> Kiểm tra manage_test -> Nếu trùng tests_id -> break
 */

// Xóa trong bảng manage_test
/*
 * Bước 1: Xóa trong bảng manage_tests
 * Bước 2: Xóa đề trong bảng tests
 */

/* Bước 0: Cập nhật tên đề, thời gian làm đề (Làm sau)
      * Bước 1: Update trong bảng question
      * Bước 2: Update trong bảng manage_test
      */


/* Khi tạo thêm câu hỏi cần làm những bước sau
 * Bước 1: Gọi tới hàm createQuestions
 * - 1.1: Dùng regx lấy những câu hỏi cho vào mảng
 * - 1.2: Validate mảng này theo cách của mình
 * - 1.3: Kiểm tra xem câu hỏi có tồn tại trong bảng questions không? Nếu trùng thì không thêm vào bảng questions mà lấy id đã có sẵn rồi
 * - 1.4: Thêm câu hỏi đó vào bảng manage_tests với index lấy ra max trong index của bộ đề đó
 * - 1.5: Chuyển hướng trang tới url chứa subject, unit, test
 */
//        $test = test::find($test_id);