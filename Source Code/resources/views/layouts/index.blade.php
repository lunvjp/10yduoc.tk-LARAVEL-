<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="fb:app_id" content="309350989506967" />
    <meta property="fb:admins" content="309350989506967">
    <meta name="google-signin-client_id" content="308122676345-ks9bl51324cu0dmctboccfrokhlj6e60.apps.googleusercontent.com">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:100,200,300,400,500,600,700&amp;subset=latin,vietnamese"/>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <style>
        @media only screen and (max-width: 800px) {
            .form-setup {
                /*display: none;*/
            }
        }
    </style>
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>--}}
    @yield('css')
</head>
<body>

@yield('modal')

<div class="mynavbar">
    <a href="{{url('')}}" title="Home"><i class="fa fa-home" style="line-height: 35px;" aria-hidden="true"></i></a>
    <a href="{{url('/do-test/1')}}" title="Làm Đề Toiec">TOEIC</a>
    <a href="{{url('/do-test/2')}}" title="Làm Đề Giải Phẫu">GIẢI PHẪU</a>
    <div id="account" style="float: right;margin-right:10px;">
        @if(Auth::check())
            @if (Auth::user()->email == 'momabz6@gmail.com')
                <a href='{{url('/admin')}}'>CHỈNH SỬA ĐỀ</a>
            @endif
            <a href='{{url('/logout')}}'>Đăng xuất</a>
            <a style="pointer-events: none">Chào {{Auth::user()->name}}</a>
            <a style='padding:0;vertical-align: top;height: 35px;'><img src="{{Auth::user()->picture}}"></a>
        @endif
    </div>
</div>

<div class="form-setup" style="border-right: none;">
    @yield('listtest')
</div>

<div class="content" style="font-family: Open Sans;font-weight: 600">
    <div id="time-container" style="display: none">
        <div class="time-container-top" style="width: 80%;display: flex;" >
            <span id="time"></span>
            <button id="reload" style="margin: auto" class="btn btn-primary"><span style="font-weight: bold">Tải lại đề thi</span></button>
        </div>
    </div>
    @yield('toeic-audio')
    <div id="questions-container">
        <div id="ajax-load" style="display: none; width: 105px; margin: auto; margin-top:20px">
            <i class="fa fa-spinner fa-spin" style="font-size: 7em; color: #D9ECFF;"></i>
        </div>
        <div id="questions">
            @yield('work')
        </div>
    </div>
    <div id="facebook">
        @yield('facebook')
    </div>
    <div class="form-add-submit" style="position: fixed;">
        @yield('testOption')
        @if (!Auth::check())
            <a type="button" href="{{url('/register')}}">Đăng kí</a>
            <a type="button" href="{{url('/login')}}">Đăng nhập</a>
        @endif
    </div>
</div>
@yield('sortoption')
{{--<script async src='//go.su/e/QA9X'></script>--}}
{{--<script src="https://apis.google.com/js/platform.js" async defer></script>--}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="{{asset('js/owl.carousel.min.js')}}"></script>
@yield('javascript')
</body>
</html>