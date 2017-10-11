{{--
1. Mới vào hiện danh sách các môn => OK
2. Ở khung bên phải hiển thị hình ảnh cộng các đề môn đó (Thiết kế giao diện cho khung bên phải hiển thị tất cả các đề: bao gồm tên đề, số người đã làm, số câu)
--}}

@extends('layouts.index')
@section('title')
    10YDuoc.tk - Cùng nhau làm đề
@endsection
@section('css')
    <style>
        .content .form-add-submit {
            left: 30%;
        }

        .form-setup {
            width: 30%;
        }

        .content {
            left: 30%;
            width: 70%;
        }

        .content  .item-question {
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
            margin-bottom: 13px;
            box-sizing: border-box;
            padding: 7px 7px;word-wrap: break-word;
        }

        a:hover, a:active, a:link{
            text-decoration: none;
        }

        .content .item-question .test-name{
            color: #007EE5;font-size: 16px
        }

        .content .item-question .test-about {
            padding-top:4px;
        }

        .content .item-question .test-about .test-about-item {
            margin-right:10px
        }
    </style>
@endsection
@section('listtest')
    @if (isset($subjects))
        @foreach ($subjects as $subject)
            <a class="btn btn-default" href="{{url('/do-test/'.$subject->id)}}" style="text-align: left; display: block;margin-bottom:7px">{{ $subject->name }}</a>
        @endforeach
    @endif
@endsection

@section('css')

@endsection

@section('work')
    @if(isset($tests))
        <div class="slide">
            <div class="container-fluid">
                @foreach($tests as $key => $test)
                    <div class="subject">
                        <div class="col-xs-12 ">
                            <div class="subject-title">
                                <h3 style="display: inline-block">{{$key}}</h3>
                                <span style="box-sizing: border-box;width:100%;margin-left:20px;text-align:right;cursor: pointer;font-weight: 400" id="seemore-btn">Xem thêm...</span>
                            </div>
                            <div class="content-list-test">
                                @if (count($test) <=6)
                                    @for($i=0;$i<count($test);$i++)
                                        <div class="col-md-4 col-sm-6 " style="padding-left: 8px;padding-right: 8px;">
                                            <a href="{{url('/do-test/'.$test[$i]['subjects_id'].'/'.$test[$i]['id'])}}">
                                                <div class="item-question btn-default">
                                                    <span class="test-name">{{$test[$i]['name']}}</span>
                                                    <div class="test-about">
                                                    <span class="test-about-item" style="color: #2ECC71;">
                                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                        <span style="font-size: 13px">{{$test[$i]['time']}} phút</span>
                                                    </span>
                                                        <span class="test-about-item" style="color: #EB5C55;">
                                                        <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                        <span style="font-size: 13px">{{$test[$i]['time']}} câu</span>
                                                    </span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endfor
                                @else
                                    @for($i=0;$i<6;$i++)
                                        <div class="col-md-4 col-sm-6 " style="padding-left: 8px;padding-right: 8px;">
                                            <a href="{{url('/do-test/'.$test[$i]['subjects_id'].'/'.$test[$i]['id'])}}">
                                                <div class="item-question btn-default">
                                                    <span class="test-name">{{$test[$i]['name']}}</span>
                                                    <div class="test-about">
                                                    <span class="test-about-item" style="color: #2ECC71;">
                                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                        <span style="font-size: 13px">{{$test[$i]['time']}} phút</span>
                                                    </span>
                                                        <span class="test-about-item" style="color: #EB5C55;">
                                                        <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                        <span style="font-size: 13px">{{$test[$i]['time']}} câu</span>
                                                    </span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endfor
                                    <div id="seemore" style="display:none">
                                        @for($i=6;$i<count($test);$i++)
                                            <div class="col-md-4 col-sm-6 " style="padding-left: 8px;padding-right: 8px;">
                                                <a href="{{url('/do-test/'.$test[$i]['subjects_id'].'/'.$test[$i]['id'])}}">
                                                    <div class="item-question btn-default">
                                                        <span class="test-name">{{$test[$i]['name']}}</span>
                                                        <div class="test-about">
                                                    <span class="test-about-item" style="color: #2ECC71;">
                                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                        <span style="font-size: 13px">{{$test[$i]['time']}} phút</span>
                                                    </span>
                                                            <span class="test-about-item" style="color: #EB5C55;">
                                                        <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                        <span style="font-size: 13px">{{$test[$i]['time']}} câu</span>
                                                    </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endfor
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

@section('testOption')

@endsection

@section('javascript')
    <script>
        $(function(){
            $("#seemore-btn").click(function(){
                $("#seemore").fadeToggle("slow");
            });
        });

    </script>
@endsection
