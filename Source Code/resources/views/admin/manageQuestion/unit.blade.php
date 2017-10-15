@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .content {
            padding-top: 5px;
            font-weight: normal !important;
            font-size: 15px !important;
        }

        .content .question {
            padding-top: 0
        }

        .content .question hr {
            margin: 10px 0 20px 0
        }

        .content .item {
            margin-left: 0
        }

        .content .item .title {
            width: 45px
        }

        #createNew input, #createNew textarea {
            display: block;
            width: 100%;
            border: 1px solid grey;
        }

        /*#createNew textarea#question {*/
        /*height: 400px;*/
        /*}*/
    </style>
@endsection

@section('modal')
    @if(isset($test_id))
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Thông báo</h4>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa đề này không?</p>
                    </div>
                    <div class="modal-footer">
                        <button id="yes" type="button" class="btn btn-success" data-dismiss="modal">Yes</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="update-index-test" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Thông báo</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            {{csrf_field()}}
                            <div class="form-group">
                                <label>Chỉ số câu hỏi bắt đầu từ</label>
                                <input class="form-control" type="number" name="start-index" required>
                            </div>
                            <button type="submit"></button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="update-start-index" class="btn btn-success">Lưu</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    @endif
@endsection

@section('listtest')
    @if (isset($tests))
        <ol>
            @foreach( $tests as $test)
                <a class="btn btn-block btn-sm btn-default"
                   href="{{url('/admin/manageQuestion/'.$subject_id.'/'.$unit_id.'/'.$test->id)}}"
                   style="text-align: left;">
                    {{ $test->name }}
                </a>
            @endforeach
        </ol>
    @endif
@endsection


@section('work')
    @if (Session::has('success-update'))
        <div class="myalert alert alert-success">
            <strong>Success!</strong> {{Session::get('success-update')}}
        </div>
    @endif
    @if (Session::has('success-delete'))
        <div class="myalert alert alert-success">
            <strong>Success!</strong> {{Session::get('success-delete')}}
        </div>
    @endif
    @if (Session::has('existSentences'))
        <div class="myalert alert alert-danger">
            <strong>Các câu hỏi đã tồn tại:</strong>
            <ol>
                @foreach (Session::get('existSentences') as $value)
                    <li>{{$value}}</li>
                @endforeach
            </ol>
        </div>
    @endif
    @if (count($errors) > 0)
        <div class="myalert alert alert-danger">
            <strong>Errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div id="listquestion">
        @if (isset($questions))
            <form method="post" name="update-form" id="update-form" action="{{url('/admin/manageQuestion/'.$subject_id.'/'.$unit_id.'/'.$test_id)}}">
                {{ csrf_field() }}
                {{--<ul id="sortable">--}}
                @foreach($questions as $question)
                    <div class='question'>
                        <input type="hidden" name='id[]' value="{{$question->id}}">
                        <div class='item'>
                            <p class='title'>Câu {{$question->pivot->index}}</p>
                            <p><textarea rows='3' id='textarea' name='name[]'>{{$question->name}}</textarea></p>
                        </div>
                        @php
                            $temp['A'] = $question->a;
                            $temp['B'] = $question->b;
                            $temp['C'] = $question->c;
                            $temp['D'] = $question->d;
                            $temp['E'] = $question->e;
                            $temp['F'] = $question->f;
                            $temp['G'] = $question->g;

                            $xhtml= '';
                            foreach ($temp as $key2 => $value2) {
                                $style = '';
                                $disable = '';
                                if (!($value2&&$value2!=null)) {
                                    $style='style="display:none"';
                                    $disable = 'disabled';
                                }

                                $xhtml .= '<div class="item" '.$style.'>
                                                <p class="answer">'.$key2.'.</p>
                                                <p><input '.$disable.' type="text" name="'.strtolower($key2).'[]" value="'.$value2.'"></p>
                                            </div>';
                            }

                            echo $xhtml;
                        @endphp
                        {{----}}
                        {{--@foreach($temp as $key2 => $value2)--}}
                        {{----}}
                        {{--@endforeach--}}
                        <div class='item'>
                            <p class='answer' style='width:40px;vertical-align: top;'>ĐS<input style='width:80%;' type='text' name='answer[]' value="{!! $question->answer !!}" maxlength="1"></p>
                            <p>Lời giải<textarea rows='2' id='textarea' name='detailofanswer[]'>{{$question->detail_answer}}</textarea></p>
                        </div>
                        <hr>
                    </div>
                @endforeach
                {{--</ul>--}}
            </form>
        @endif
    </div>
    <div id="createNew" style="margin: 0 10px 10px 10px;display: none">
        <form id="add-form" action="{{url('/admin/manageQuestion/'. $subject_id .'/'.$unit_id)}}" method="post">
            {{ csrf_field() }}
            <div class="form-inline form-group">
                <div class="form-group">
                    <label for="name">Tên:</label>
                    <input type="text" class="form-control" id="name" placeholder="Nhập tên đề" name="name" required>
                </div>
                <div class="form-group">
                    <label for="time">Thời gian (phút):</label>
                    <input type="number" class="form-control" id="time" placeholder="Nhập thời gian" name="time" required>
                </div>
                <div class="form-group checkbox" style="vertical-align: bottom">
                    <label><input class="remember" type="checkbox" name="remember">Thêm đáp án</label>
                </div>
                <div class="form-group answerCount" style="display: none">
                    <label class="radio-inline"><input type="radio" name="answer" value="2">2</label>
                    <label class="radio-inline"><input type="radio" name="answer" value="3">3</label>
                    <label class="radio-inline"><input type="radio" name="answer" value="4" checked>4</label>
                    <label class="radio-inline"><input type="radio" name="answer" value="5">5</label>
                    <label class="radio-inline"><input type="radio" name="answer" value="6">6</label>
                    <label class="radio-inline"><input type="radio" name="answer" value="7">7</label>
                </div>
            </div>

            <div class="form-group answerOption" style="display: none;">
                <div class="form-group">
                    <label for="a">A:</label>
                    <input type="text" class="form-control" name="a" disabled>
                </div>
                <div class="form-group">
                    <label for="b">B:</label>
                    <input type="text" class="form-control" name="b" disabled>
                </div>
                <div class="form-group">
                    <label for="c">C:</label>
                    <input type="text" class="form-control" name="c" disabled>
                </div>
                <div class="form-group">
                    <label for="d">D:</label>
                    <input type="text" class="form-control" name="d" disabled>
                </div>
                <div class="form-group">
                    <label for="e">E:</label>
                    <input type="text" class="form-control" name="e" disabled>
                </div>
                <div class="form-group">
                    <label for="f">F:</label>
                    <input type="text" class="form-control" name="f" disabled>
                </div>
                <div class="form-group">
                    <label for="g">G:</label>
                    <input type="text" class="form-control" name="g" disabled>
                </div>
            </div>

            <div class="form-group">
                <label for="question">Nội dung:</label>
                <textarea class="form-control" rows="15" id="question" name="question" required></textarea>
            </div>

            <button class="btn btn-success">Lưu</button>
            <button type="button" class="btn btn-danger">Xóa</button>
        </form>
    </div>

    @if (isset($test_id))
        <div id="createNewQuestion" style="margin: 0 10px 10px 10px;display: none">
            <form id="add-question-form"
                  action="{{url('/admin/manageQuestion/'. $subject_id .'/'.$unit_id.'/'.$test_id.'/create')}}"
                  method="post">
                {{ csrf_field() }}
                <div class="form-inline form-group">
                    <div class="form-group checkbox" style="vertical-align: bottom">
                        <label><input class="remember" type="checkbox" name="remember">Thêm đáp án</label>
                    </div>
                    <div class="form-group answerCount" style="display: none">
                        <label class="radio-inline"><input type="radio" name="answer" value="2">2</label>
                        <label class="radio-inline"><input type="radio" name="answer" value="3">3</label>
                        <label class="radio-inline"><input type="radio" name="answer" value="4" checked>4</label>
                        <label class="radio-inline"><input type="radio" name="answer" value="5">5</label>
                        <label class="radio-inline"><input type="radio" name="answer" value="6">6</label>
                        <label class="radio-inline"><input type="radio" name="answer" value="7">7</label>
                    </div>
                </div>

                <div class="form-group answerOption" style="display: none;">
                    <div class="form-group">
                        <label for="a">A:</label>
                        <input type="text" class="form-control" name="a" disabled>
                    </div>
                    <div class="form-group">
                        <label for="b">B:</label>
                        <input type="text" class="form-control" name="b" disabled>
                    </div>
                    <div class="form-group">
                        <label for="c">C:</label>
                        <input type="text" class="form-control" name="c" disabled>
                    </div>
                    <div class="form-group">
                        <label for="d">D:</label>
                        <input type="text" class="form-control" name="d" disabled>
                    </div>
                    <div class="form-group">
                        <label for="e">E:</label>
                        <input type="text" class="form-control" name="e" disabled>
                    </div>
                    <div class="form-group">
                        <label for="f">F:</label>
                        <input type="text" class="form-control" name="f" disabled>
                    </div>
                    <div class="form-group">
                        <label for="g">G:</label>
                        <input type="text" class="form-control" name="g" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label for="question">Nội dung:</label>
                    <textarea class="form-control" rows="15" id="question" name="question" required></textarea>
                </div>

                <button class="btn btn-success">Lưu</button>
                <button type="button" class="btn btn-danger">Xóa</button>
            </form>
        </div>
    @endif


@endsection
@section ('sortoption')
    @if (isset($test_id))
    <div id="sortquestion" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 90%">
            <div class="modal-content">
                <form method="post" action="{{url('/admin/manageQuestion/'. $subject_id .'/'.$unit_id.'/'.$test_id)}}">
                    {{csrf_field()}}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Sắp xếp câu hỏi</h4>
                    </div>
                    <div class="modal-body" style="height: 70%;overflow-y: auto;">
                        <table class="table table-hover table-condensed table-responsive">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>DS</th>
                                    <th>ID</th>
                                    <th>Câu hỏi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="sortable">
                            @if (isset($questions))
                                @foreach ($questions as $key => $question)
                                    <tr id="{{$question->id}}">
                                        <th>{{$question->pivot->index}}</th>
                                        <th><input class="form-control" name="answer[]" value="{{$question->answer}}" maxlength="1"></th>
                                        <th>{{$question->id}}</th>
                                        <th>{{$question->name}}</th>
                                        <th>
                                            <button type="button" class="btn btn-danger btn-sm delete-question-btn" id="{{$question->id}}">Xóa</button>
                                        </th>
                                        <input type="hidden" name="index[]" value="{{$question->id}}" >
                                        <input type="checkbox" hidden name="checkDelete[]" value="{{$question->id}}">
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Lưu</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    @endif
@endsection
@section('testOption')
    <button type="button" id="add-button">Thêm đề</button>
    <button type="button" id="add-question-button">Thêm câu hỏi</button>
    <button type="button" data-toggle="modal" data-target="#sortquestion" id="sort-question-button">Sắp xếp/Xóa câu hỏi</button>
    <button type="button" id="update-button">Cập nhật đề</button>
    <button data-toggle="modal" data-target="#update-index-test">Cập nhật chỉ số câu</button>
    <a type="button" id="delete-button" data-toggle="modal" data-target="#myModal">Xóa đề</a>
@endsection
@section('javascript')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        function createFormAddQuestion(option) {
            //            Bước 1. Ban đầu kích vào thêm đáp án (id=remmember), thì khung id=answerCount hiện ra (gồm 6 radio)
            //            - Lúc này đồng thời hiện ra luôn khung id=answerOption với 4 đáp án mặc định
            //            Bước 2. Khi admin thay đổi radio thì tự động thằng mỗi phương án cũng đóng lại
            var createAnswer = $("#" + option + " .remember");
            createAnswer.prop('checked', false); // Unchecks it

            var input = $("#" + option + " .answerOption input");
            // Bước 1:
            createAnswer.change(function () {
                var count = $("#" + option + " input[name=answer]:checked").val(); // == string
                if (this.checked) { // Nếu nó check vào thì có 2 điều xảy ra
                    // 1. Khung answerCount xuất hiện với mặc định số 4
                    $("#" + option + " .answerCount").show();
                    // 2. Khung answerOption xuất hiện với mặc định 4 đáp án
                    var temp = Number(count); // temp == number
                    input.each(function (key) {
                        if (key === temp) {
                            return false;
                        }
                        $(this).prop('disabled', false);
                    });
                    $("#" + option + " .answerOption").show();
                }
                else { // Nếu nó tắt check thì cả 2 khung đều biến mất và đồng thời từng input disabled lại
                    input.each(function () {
                        $(this).prop('disabled', true);
                    });
                    $("#" + option + " .answerCount").hide();
                    $("#" + option + " .answerOption").hide();
                }
            });

            // Bước 2:
            $("#" + option + " input[type=radio]").change(function () {
                var count = $("#" + option + " input[name=answer]:checked").val();
                var temp = Number(count); // temp == number
//                    input = $("#createNewQuestion .answerOption input");
                input.each(function (key) {
                    if (key >= temp) $(this).prop('disabled', true);
                    else $(this).prop('disabled', false);
                });
            });
        }


        $(function () {
            var item = $("#sortable");
            item.sortable({
                placeholder: "ui-state-highlight"
            });
            item.disableSelection();


            $(".delete-question-btn").click(function () {
                var id = $(this).attr("id");
                // Click nút xóa
                $("tr#" + id).fadeOut(); // prop('checked',true);
                $("input[value="+id+"]").prop('checked',true);
            });

            $("#add-button").click(function () {
                $(".myalert").hide();
                $("#listquestion").hide();
                $("#createNewQuestion").hide();
                $("#createNew").show();
            });
            $("#add-question-button").click(function () {
                $(".myalert").hide();
                $("#listquestion").hide();
                $("#createNew").hide();
                $("#createNewQuestion").show();
            });
            $("#update-button").click(function () {
                $("#update-form").submit();
            });
            $("#yes").click(function () {
                @if(isset($test_id))
                    window.location = "{{url('/admin/manageQuestion/'. $subject_id .'/'.$unit_id .'/'.$test_id.'/delete')}}";
                @endif
            });
            @if (isset($test_id))
            $("#update-start-index").click(function(){
                var index = $('input[name=start-index]').val();
                var listid = $('#update-form '+ 'input[name="id[]"]').map(function(){return $(this).val();}).get();
                $.ajax({
                    url: '{{url('/admin/manageQuestion/'.$subject_id .'/'.$unit_id .'/'.$test_id.'/updateStartIndex')}}',
                    type: 'post',
                    data: {
                        index: index,
                        id: listid,
                        _token: '{{Session::token()}}'
                    },
                    success: function (data) {
//                        console.log(data);
                        location.reload();
                    }
                });
            });
            @endif
//            $("#sort-question-button").click(function(){
//
//            });
//            $(".answer input").change(function(){
//                $(this).focus();
//            });
//            $(".answer input").change(function() {
//                var inputs = $(this).closest('form').find(':input');
//                inputs.eq( inputs.index(this)+ 1 ).focus();
//            });

//            var focusables = $(".item-sort-form input:focusable");
            var focusables2 = $("input.form-control");
            focusables2.keyup(function (e) {
                var maxchar = false;
                if ($(this).attr("maxlength")) {
                    if ($(this).val().length >= $(this).attr("maxlength"))
                        maxchar = true;
                }
//                if (e.keyCode === 13 || maxchar) {
                if (maxchar) {
                    var current = focusables2.index(this),
                        next = focusables2.eq(current + 1).length ? focusables2.eq(current + 1) : focusables2.eq(0);
                    next.focus();
                }
            });

            createFormAddQuestion('createNew');
            createFormAddQuestion('createNewQuestion');
        });
    </script>
@endsection