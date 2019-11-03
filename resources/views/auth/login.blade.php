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

        @media only screen and (max-width: 1140px) {
            .btn-socialite {
                display: block;
                margin-bottom: 10px;
            }
        }


    </style>
@endsection
@section('listtest')
    <form style="margin-top:10px" class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
        {{ csrf_field() }}
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

            <label for="email" class="col-md-4 control-label">E-Mail Address</label>
            <div class="col-md-7">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Mật khẩu</label>
            <div class="col-md-7">
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-12" style="text-align: center;margin-bottom: 10px">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-btn fa-sign-in"></i> Đăng nhập
            </button>
            <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
        </div>
        {{--<div class="col-sm-12" style="text-align: center">--}}
            {{--<a href="javascript:void(0);" onclick="fbLogin()" class="btn-socialite btn btn-primary">--}}
                {{--<i class="fa fa-facebook fa-lg"></i> Facebook Login</a>--}}
            {{--<a href="{{url('/auth/google')}}" class="btn-socialite btn btn-danger">--}}
                {{--<i class="fa fa-google-plus fa-lg" aria-hidden="true"></i> Google Login</a>--}}
        {{--</div>--}}
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

        function fbLogin() {
            FB.login(function (response) {
                if (response.authResponse) { // Đăng nhập thành công ở ngay đây rồi
                    getFbUserData();
                }
            }, {scope: 'public_profile,email'});
        }

        function getFbUserData() {
            FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
                function (response) {
                    // Save user data
                    saveUserData(response);
                    // $("#form-fade").submit();
                });
        }

        function saveUserData(userData) {
            $.post('{{url('facebook')}}',
                {oauth_provider: 'facebook', _token: '{{Session::token()}}',userData: JSON.stringify(userData)},
                function (data) {
                    // $("#account").html(data);
                    window.location.replace('/do-test');
                    // return true;
                });
        }

        $(function(){
            $("#seemore-btn").click(function(){
                $("#seemore").fadeToggle("slow");
            });
        });
    </script>
@endsection
