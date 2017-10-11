@extends('layouts.index')
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
    <form style="margin-top:10px" class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">Name</label>

            <div class="col-md-7">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>

                @if ($errors->has('name'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

            <div class="col-md-7">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Password</label>

            <div class="col-md-7">
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

            <div class="col-md-7">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-btn fa-user"></i> Register
                </button>
            </div>
        </div>
    </form>
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
@section('javascript')
    <script>
        $(function(){
            $("#seemore-btn").click(function(){
                $("#seemore").fadeToggle("slow");
            });
        });
    </script>
@endsection