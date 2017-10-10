<?php
$menu = '<a href="#" title="Làm Đề Giải Phẫu">GIẢI PHẪU</a>
        <a href="#" title="Làm Đề Sinh Di Truyền">SINH DI TRUYỀN</a>
        <a href="#" title="Làm Đề Tiếng Anh">ENGLISH</a>';

if (isset($_SESSION['id'])) { // đăng nhập thành công
    header("location: .?controller=user&action=loadListTest&subject_id=1");
    exit();
}

if (!isset($_SESSION['answer'])) $_SESSION['answer'] = $this->content['answer'];
?>
<html>
<head>
    <title>10YDuoc.tk - Cùng nhau luyện đề</title>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="./public/css/style.css">


    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!--    <script type="text/javascript" src="./views/load.js"></script>-->
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .content {
            border: 1px solid grey;
            padding:0;
            box-sizing: border-box;
            position: fixed;
            top: 35px;
            bottom: 38px;
            left: 30%;
            width: 70%;
            overflow-x: hidden;
            overflow-y: auto;
            background-color: white;
            border-top: none;
        }

        .content .question {
            font-family: "Times New Roman", sans-serif;
            padding-top: 20px;
        }

        .content .question hr {
            width: 100%;
            height: 1px;
            background-color: grey;
            margin: 0;
        }

        .content #id {
            background:  lightskyblue;
        }

        .auto-padding {
            padding-top: 40px;
        }
    </style>
</head>
<body>

<div class="mynavbar">
    <a href="#" title="Home"><i class="fa fa-home" style="line-height: 35px;"  aria-hidden="true"></i></a>
    <a href="#" title="Làm Đề Giải Phẫu">GIẢI PHẪU</a>
    <a href="#" title="Làm Đề Sinh Di Truyền">SINH DI TRUYỀN</a>
    <a href="#" title="Làm Đề Tiếng Anh">ENGLISH</a>
    <div id="account" style="float: right;margin-right:10px;">
        <?php
        if (isset($_SESSION['info'])) echo $_SESSION['info'];
        ?>
    </div>
</div>

<div class="form-setup" style="border-right: none;"> <!-- Hiện các bộ đề đã làm ở đây -->
    <a href="javascript:void(0);" onclick="fbLogin()" id="fbLink"><img style="width:100%" src="./public/images/fblogin.png"></a>
    <ol>
        <?php echo $this->content['listTest']; ?>
    </ol>
</div>

<div class="content"> <!-- Hiển thị số câu đã làm ở đây -->

        <div id="time"
             style="position:fixed;width:100%;background: lightskyblue; height:40px;border-bottom:1px solid grey;font-size:25px;font-weight: bold;font-family: Arial,sans-serif;color: #ffff80;line-height: 40px;padding-left:10px;">
            <span>BẮT ĐẦU</span></div>

    <div id="result"><?php
        if (isset($_SESSION['result'])) {
            echo $_SESSION['result'];
        }
        ?></div>

    <div id="ajax-load" style="display: none; height: 100px; width: 160px; margin: auto; margin-top:60px">
        <i class="fa fa-spinner fa-spin" style="font-size: 7em; color: #D9ECFF;"></i>
    </div>


    <div id="choiceuser" style="padding-top: 40px">
        <?php
        if (!isset($_SESSION['result'])) echo $this->content['listQuestion'];
        else unset($_SESSION['result']);
        ?>
    </div>


    <div class="form-add-submit" style="position: fixed;">
        <form id="form-fade" method="post"></form>
<!--        <button type="button" id="wrong-button">Bài làm sai</button>-->
<!--        <button type="button" id="right-button">Bài làm đúng</button>-->
        <button type="button" id="submit-button">Nộp bài</button>
    </div>
</div>
<script async src='//go.su/e/QA9X'></script>
<script type="text/javascript" src="./views/load.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>

