@extends('layouts.index')
@section('css')
    <style>
        .content .form-add-submit {
            left: 20%;
        }

        .content .item input {
            min-width: 26px;
            min-height: 13px;
        }

        #questions-container {
            position: fixed;
            top: 35px;
            bottom: 40px;
            width: 40%;
            overflow-x: hidden;
            overflow-y: auto;
            text-align: justify;
        }

        #facebook {
            position: fixed;
            top: 35px;
            left: 60.1%;
            right: 0;
            bottom: 40px;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .form-setup {
            width: 20%;
        }

        .content {
            left: 20%;
            width: 80%;
            font-weight: normal !important;
            font-size: 15px !important;
        }

        .content .question {
            padding-top: 15px;
        }

        .content #id {
            background: lightskyblue;
        }

        .auto-padding {
            padding-top: 40px;
        }

        a:hover {
            background: none;
        }
    </style>
@endsection
@section('modal')
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Thông báo</h4>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn làm đề này không?</p>
            </div>
            <div class="modal-footer">
                <button id="yes" type="button" class="btn btn-success" data-dismiss="modal">Yes</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('listtest')
    @if (isset($subjects))
        @foreach( $subjects as $subject)
            <a class="btn btn-default" href="{{url('/do-test/'.$subject->id)}}" style="text-align: left; display: block;margin-bottom:7px">
                {{ $subject->name }}
            </a>
        @endforeach
    @endif
    @if (isset($listTest))
        @foreach( $listTest as $test)
            @if ($test['check'])
                <a style="text-align: left" class="btn btn-sm btn-block btn-success" href="{{url('/do-test/'.$subject_id.'/'.$test['id'])}}" >{{ $test['name']}}
                    <span>- {{$test['count']}}/{{$test['all']}} câu</span>
                </a>
            @else {{-- href="{{url('/do-test/'.$subject_id.'/'.$test['id'])}}" --}}
                <a data-testid="{{$test['id']}}" onclick="getLink({{$test['id']}})" style="text-align: left;" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-block btn-default" >{{ $test['name'] }}</a>
            @endif
        @endforeach
    @endif
@endsection
@section('toeic-audio')
    @if (isset($questions) && $subject_id == 1 && !Session::has('checkDoTest'))
    <audio id="toeic-audio" class="btn-block" controls style="position: fixed; width: 40%; ">
        <source src="{{asset('upload/'.$audio)}}">
    </audio>
    @endif
@endsection

@section('work')
    @if (Session::has('checkDoTest'))
        <div style="margin: 10px" class="alert alert-info">
            <strong>Thông báo!</strong> Bạn chưa làm câu nào trong đề này. Click vào <a href="#" id="newtest" class="alert-link">ĐÂY</a> để làm
        </div>
    @else
        @if (isset($questions))
            @if (!empty($questions))
                @foreach($questions as $key => $value)
                    <div class="question {{$value->answer == $value->answerofuser ? 'right' : 'wrong'}}">
                        <div class="item">
                            <p class="title">Câu {{$value->index}}.</p>
                            <p class="title-content listen">{{$value->name}}</p>
                        </div>

                        @php
                            $temp['A'] = $value->a;
                            $temp['B'] = $value->b;
                            if ($value->c) $temp['C'] = $value->c;
                            if ($value->d) $temp['D'] = $value->d;
                            if ($value->e) $temp['E'] = $value->e;
                            if ($value->f) $temp['F'] = $value->f;

                            $xhtml = '';
                            foreach($temp as $key2 => $value2) {
                                $xhtml .= '<div class="item">
                                    <p class="answer">'.$key2.'.</p>
                                    <p class="listen">' . $value2 . '</p>
                                </div>';
                            }

                            echo $xhtml;
                        @endphp
                        <p class="insert-answer" style="color: {{$value->answer == $value->answerofuser ? 'blue' : 'red'}};">{{strtoupper($value->answer)}} - Trả lời {{strtoupper($value->answerofuser)}}</p>
                    </div>
                @endforeach
            @else {{-- Đã click vào đề nhưng chưa làm câu nào --}}
                <div style="margin: 10px" class="alert alert-info">
                    <strong>Thông báo!</strong> Bạn chưa làm câu nào trong đề này.
                </div>
            @endif
        @endif
    @endif
@endsection
@section('testOption')
    <div id="dotest" style="display: none">
        <button type="button" id="submit-button">Nộp bài</button>
    </div>
    @if (!Session::has('checkDoTest') && isset($test_id))
    <div id="seetest">
        <button type="button" id="result-button">Kết quả</button>
        <button type="button" id="notdone-button">Bài chưa làm</button>
        <button type="button" id="wrong-button">Bài làm sai</button>
        <button type="button" id="right-button">Bài làm đúng</button>
        <button type="button" id="facebook-button">Bật/Tắt bình luận</button>
        @if ($subject_id == 1)
            <div data-toggle="tooltip" data-placement="top" title="Bài làm luôn luôn được ẩn mỗi khi hiển thị kết quả" style="display: inline; vertical-align: middle;padding: 5px" class="panel checkbox">
                <label style="font-weight: bold;"><input type="checkbox" id="listen-practice">LUYỆN NGHE</label>
            </div>
            <div style="display: none; margin-left: 5px; vertical-align: middle; padding: 5px" class="panel" id="listen-practice-option">
                <span style="vertical-align: middle;"><input style="width: 17px; height: 17px" type="radio" name="showQuestion" value="false" checked></span>
                <span style="padding-left: 2px">Ẩn Câu Hỏi</span>
                <span style="vertical-align: middle;"><input style="width: 17px; height: 17px;margin-left: 4px" type="radio" name="showQuestion" value="true"></span>
                <span style="padding-left: 2px">Hiện Câu Hỏi</span>
            </div>
        @endif
    </div>
    @endif
@endsection
@section('facebook')
    @if (isset($test_id))
    <div id="player">
        <div class="fb-comments" data-href="{{url('/do-test/'.$subject_id.'/'.$test_id)}}" data-width="100%"  data-numposts="15"></div>
    </div>
    @endif
@endsection

@section('javascript')
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.10&appId=309350989506967";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<script src="{{asset('js/jquery.countdown.min.js')}}"></script>
<script>
@if (isset($subject_id))
    //--------------------------------------------------------------
    // Check TOEIC SUBJECT and User've done
    @if($subject_id == 1 && !Session::has('checkDoTest'))
    @if (isset($listen))
        $("#listen-practice").prop('checked',true);
        if (!($('form#form-do-more-question').length)) { // Nếu người dùng không phải đang ở Bài Chưa Làm
            $('#listen-practice-option' + ' input[value="false"]').prop('checked',true);
            $("#listen-practice-option").css('display','inline');
            $(".listen").hide();
            $('.title, .answer').after('<input class="form-control">');
        }
    @endif
    $(function(){
        var listen_practice = $("#listen-practice");

        $("#questions-container").css('margin-top','32px');
//        listen_practice.prop('checked',false);

        listen_practice.change(function(){
            $("#questions").hide();
            $("#ajax-load").show();
            $.ajax({
                url: '{{url('do-test/'.$subject_id.'/practiceListening')}}',
                type: 'POST',
                data: {
                    check: $("#listen-practice:checked").val(),
                    _token: '{{Session::token()}}'
                },
                success: function(data) {
                    console.log(data);
                    if ($("#listen-practice:checked").val()) { // BẬT Chế Độ Luyện Nghe
                        if (!($('form#form-do-more-question').length)) {
                            $('#listen-practice-option' + ' input[value="false"]').prop('checked',true);
                            $("#listen-practice-option").css('display','inline');
                            $(".listen").hide();
                            $('.title, .answer').after('<input class="form-control">');
                            // Gọi Ajax lưu vào database là nó muốn luyện nghe mặc định
                        }
                    } else { // TẮT Chế Độ Luyện Nghe
                        $(".listen").fadeIn();
                        $('#listen-practice-option').hide();
                        $(".item input.form-control").remove();
                        // Gọi ajax lưu vào database là nó ko muốn luyện nghe mặc định
                    }
                    $("#ajax-load").hide();
                    $("#questions").show();
                }
            });


//            if (this.checked) { // BẬT Chế Độ Luyện Nghe
//                if (!($('form#form-do-more-question').length)) {
//                    $('#listen-practice-option' + ' input[value="false"]').prop('checked',true);
//                    $("#listen-practice-option").css('display','inline');
//                    $(".listen").hide();
//                    $('.title, .answer').after('<input class="form-control">');
//                    // Gọi Ajax lưu vào database là nó muốn luyện nghe mặc định
//                }
//            } else { // TẮT Chế Độ Luyện Nghe
//                $(".listen").fadeIn();
//                $('#listen-practice-option').hide();
//                $(".item input.form-control").remove();
//
//                // Gọi ajax lưu vào database là nó ko muốn luyện nghe mặc định
//            }

            // Nói chung mỗi khi thay đổi đều gọi ajax, vậy thôi
//            alert(this.checked);

        });

        $('#listen-practice-option'+ ' input[name=showQuestion]').change(function(){
            var check = $('#listen-practice-option'+ ' input[name=showQuestion]:checked').val();
            if (check === 'true') {
                $('.listen').css({'display':'block','margin':'0'}).fadeIn();
            } else {
                $('.listen').hide();
            }
        });
    });
    @endif
    $('[data-toggle="tooltip"]').tooltip();
    //--------------------------------------------------------------

    var timeObject = $('#time');

    function getDayFromNow(time) {
        return new Date(new Date().valueOf() + time * 60 * 1000);
    }

    timeObject.countdown(getDayFromNow(0))
        .on('update.countdown', function(event) {
            var format = '%M:%S';
            $(this).html(event.strftime(format));
        })
        .on('finish.countdown', function(event) {
            setTimeout(function(){
                $("#form-do-test").submit();
            },1000);
        });

    var test_id;
    var checkDoingTest = null;

    function getLink(id) {
        var check = false;

        if (timeObject.text() && checkDoingTest!==id) {
            check = true;
            $(".modal-body").html('<p>Hãy nộp bài rồi làm tiếp đề khác nhé!</p>');
            $(".modal-footer").html('<button type="button" class="btn btn-success" data-dismiss="modal">OK</button>');
        }

        if (!check) {
            test_id = id;
            $(".modal-body").html('<p>Bạn có chắc chắn muốn làm đề này không?</p>');
            $(".modal-footer").html('<button id="yes" type="button" class="btn btn-success" data-dismiss="modal">Yes</button>' +
                '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>');
        }
    }
@if (isset($test_id))
    test_id = '{{$test_id}}';
    // Dùng hàm này để gọi ajax khi làm đề
    // Nếu xem kết quả thì dùng route như bình thường

    var result;

    $(function () {
        $(document).on ("click", "#form-do-more-question input", function () {
            var id = $(this).attr("class");
            var answerofuser = $(this).val();
            $('input.'+id).prop('disabled',true).css('cursor','default');
            temp = 'div#'+id;

            var answer = $('#' + id + ' .key').val();
            var check = answerofuser.toUpperCase() === answer.toUpperCase();
            var color = check ? 'blue' : 'red';
            var panelQuestion = $("#" + id + ' .insert-answer');
            panelQuestion.html('<b>'+(check ? 'Đúng' : 'Sai') + ' - Đáp án '+ answer+'</b>').css('color',color).show();
            $.ajax({
                url: '{{url('do-test/'.$subject_id.'/doQuestion')}}',
                type: 'POST',
                data: {
                    question_id: id,
                    test_id: test_id,
                    type: 'more',
                    answerofuser: answerofuser,
                    _token: '{{Session::token()}}'
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });



        $("#notdone-button").click(function(){
            $("#questions").empty();
            $("#ajax-load").show();
            $.ajax({
                {{--url: '{{url('do-test/'.$subject_id.'/'.$test_id)}}', // ok--}}
                url: '{{url('do-test/'.$subject_id.'/'.$test_id)}}', // ok
                type: 'POST',
                data: {
                    subject_id: '{{$subject_id}}',
                    test_id: test_id,
                    type: 'more',
                    _token: '{{Session::token()}}'
                },
//                dataType: 'json',
                success: function(data) {
                    $("#questions").html(data.questions);
                    $("#ajax-load").hide();
                }
            });
        });

        // Lỗi là do nó gọi 2 hàm cùng 1 lúc, chứ ko phải chờ nội dung được in ra rồi nó check.
        // Tìm cách biết ko phải dùng callback mà check được hàm nào trước sau
        $("#result-button").click(function(){
            loadQuestionDone(null);
        });
        $("#wrong-button").click(function () {
            loadQuestionDone('wrong');
        });
        $("#right-button").click(function () {
            loadQuestionDone('right');
        });

        $("#show-button").click(function(){
            $(".title-content, .question .item p span").fadeToggle();
        });
        var dem=0;
        $("#facebook-button").click(function(){
            dem++;
            if (dem%2 !== 0) {
                $("#questions-container").animate({width: '80%'});
                $("#facebook").animate({left: '100%'});
                $("#toeic-audio").animate({width: '80%'});
            } else {
                $("#questions-container").animate({width: '40%'});
                $("#facebook").animate({left: '60%'});
                $("#toeic-audio").animate({width: '40%'});
            }
        });
    });

    function loadQuestionDone(type) {
        $("#questions").empty();
        $("#ajax-load").show();
        $.ajax({
            url: '{{url('do-test/'.$subject_id.'/'.$test_id.'/showQuestions')}}', // ok
            type: 'POST',
            data: {
                subject_id: '{{$subject_id}}',
                test_id: test_id,
                type: type,
                _token: '{{Session::token()}}'
            },
            success: function(data) {
                $("#questions").html(data.questions);
                //------------------------------------------------------------------------------
                // Check Listening Option //
                @if ($subject_id == 1 && !Session::has('checkDoTest'))
                if ($("#listen-practice:checked").val()) { // BẬT Chế Độ Luyện Nghe
                    if (!($('form#form-do-more-question').length)) { // Nếu ko ở trong Bài Chưa Làm mới thực hiện
                        $('#listen-practice-option' + ' input[value="false"]').prop('checked',true); // Chỉnh mặc định: Ẩn câu hỏi
                        $("#listen-practice-option").css('display','inline'); // Hiện tùy chọn Show/Hide Questions trong chế độ nghe
                        $(".listen").hide(); // Ẩn câu hỏi
                        $('.title, .answer').after('<input class="form-control">'); // Thêm input để luyện vào
                    }
                } else { // TẮT Chế Độ Luyện Nghe
                    $(".listen").fadeIn(); // Hiển thị câu hỏi lên
                    $('#listen-practice-option').hide(); // Tắt tùy chọn Show/Hide Questions
                    $(".item input.form-control").remove(); // Loại bỏ các input
                }
                @endif
                $("#ajax-load").hide();
            }
        });
    }
@endif
    function reloadTestWhenDoingTest() {
        $("#toeic-audio").remove();
        $("#questions").empty();
        $("#ajax-load").show();
        $("#dotest").show();
        $("#seetest").hide();
        $("#facebook").remove();

        $.ajax({
            @if(isset($test_id)) url: '{{url('do-test/'.$subject_id.'/'.$test_id)}}',
            @else url: '{{url('do-test/'.$subject_id)}}',
            @endif
            type: 'post',
            data: {
                subject_id: '{{$subject_id}}',
                test_id: test_id,
                _token: '{{Session::token()}}'
            },
            success: function(data) {
                //----------------------------------------------
                $("#time-container").show();
                $("#questions").html(data.questions);
                $("#questions-container").css({'margin-top':'40px','width':'80%'}); // Để khoảng trống cho đếm giờ
                var check = false;
                if (!timeObject.text()) {
                    @if ($subject_id == 1)
                    if (!$('#mp3').length) $('.time-container-top').append('<audio id="mp3" style="margin: auto; width: 70%;" controls autoplay>' +
                        '<source src="{{asset('upload')}}/'+data.link+'"></audio>');
                    if ($('#mp3').length) {
                        var audio = document.getElementById('mp3');
                        audio.oncanplaythrough = function(){
                            timeObject.countdown(getDayFromNow(data.time));
                            $("#ajax-load").hide();
                            check = true;
                        };
                        audio.addEventListener("ended", function()
                        {
                            $("#form-do-test").submit();
                        });
                    }
                    @else
                        timeObject.countdown(getDayFromNow(data.time));
                    @endif
                }
                if (!check) $("#ajax-load").hide();
                $("input[name=testid]").val(test_id);
            }
        });
    }

    $(document).on ("click", "#yes, #newtest, #reload", function () {
        // Cho biết là đang làm 1 đề nào đó
        checkDoingTest = test_id;
        //---------------------------------
        reloadTestWhenDoingTest();
    });

    $(document).on ("click", "#form-do-test input", function () {
        var id = $(this).attr("class");
        var answerofuser = $(this).val();
        temp = 'div#'+id;
//            $(temp).css({'pointer-events':'none','background-color':'#e6e6e6'});
        $('input.'+id).prop('disabled',true).css('cursor','default');
        $.ajax({
            url: '{{url('do-test/'.$subject_id.'/doQuestion')}}',
            type: 'POST',
            data: {
                question_id: id,
                test_id: test_id,
                answerofuser: answerofuser,
                _token: '{{Session::token()}}'
            },
            success: function(data) {
                $(temp).hide();
            }
        });
    });

    $("#submit-button").click(function(){
        $("#form-do-test").submit();
    });
@endif
    window.fbAsyncInit = function () {
        FB.init({
            appId: '309350989506967', // FB App ID 503740856637847 lunvjp@gmail.com
            cookie: true,  // enable cookies to allow the server to access the session
            xfbml: true,  // parse social plugins on this page
            version: 'v2.8' // use graph api version 2.8
        });
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
//        js.src = "//connect.facebook.net/vi_VN/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));


    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.10&appId=309350989506967";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
@endsection