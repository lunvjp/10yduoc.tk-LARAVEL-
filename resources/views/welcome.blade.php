<!DOCTYPE html>
<html>
<head>
    <title>Time | Make Question</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:100,200,300,400,500,600,700&amp;subset=latin,vietnamese"/>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    {{--<link href="{{asset('css/style.css')}}" rel="stylesheet">--}}

    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>--}}
    {{--@yield('css')--}}
    <style>
        body {
            background-color: gray;
        }

        .content {
            background-color: white;
            position: fixed;
            bottom: 10px;
            top: 10px;
            left: 20px;
            right: 20px;
            z-index: 1050;
            width: 95%;
            margin: 20px auto;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
            /*margin-bottom: 13px;*/
            /*box-sizing: border-box;*/
            /*padding: 7px 7px;word-wrap: break-word;*/
        }
    </style>
</head>
<body>


<div class="content">
    <div class="container-fluid">
        <div id="success" style="margin: 10px; display: none">
            <div class="alert alert-success">
                <strong>Success!</strong> Thank you very much!
            </div>
        </div>

        <form method="post" action="https://postmail.invotes.com/send" id="email_form">
            <input type="hidden" name="access_token" value="av1ftnuxe1jjng3jxuz9a0ck" />
            <input type="hidden" name="success_url" value=".?message=Email+Successfully+Sent%21&isError=0" />
            <input type="hidden" name="error_url" value=".?message=Email+could+not+be+sent.&isError=1" />
        <table class="table table-hover table-responsive table-hover">
            <thead>
                <tr>
                    <th>Câu</th>
                    <th>Answer</th>
                    <th>WH-question</th>
                    <th>Yes-No Question</th>
                    <th>Or Question</th>
                    <th>Tag Question</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
            @if(isset($questions))
                @foreach($questions as $index => $question)
                    <tr class="{{$index %2==0 ? '' : 'info'}}">
                        <th>{{$index+1}}</th>
                        <th>{{$question->answer}}</th>
                        <th class="col-md-2">{{$question->wh}}</th>
                        <th class="col-md-2">{{$question->yes_no}}</th>
                        <th class="col-md-2">{{$question->or}}</th>
                        <th class="col-md-2">{{$question->tag}}</th>
                        <th class="col-md-2"><input class="form-control" name="extra_comment_{{$index+1}}"></th>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
            <div class="form-group">
                <label for="nhanxet">Comment: (It's mailed to momabz6@gmail.com)</label>
                {{--<input hidden type="text" name="subject" placeholder="Your Name" value="1">--}}
                {{--<input hidden type="text" name="text" placeholder="Your Name" value="1">--}}
                <textarea class="form-control" rows="5" name="extra_nhanxet"></textarea>
            </div>
            <div class="form-group">
                <button id="submit_form" class="btn btn-success" name="send">Send</button>

            </div>
        </form>
    </div>
</div>

<script>
    var submitButton = document.getElementById("submit_form");
    var form = document.getElementById("email_form");
    form.addEventListener("submit", function (e) {
        setTimeout(function() {
            submitButton.value = "Sending...";
            submitButton.disabled = true;
            $("#success").show();
        }, 1);
    });
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>