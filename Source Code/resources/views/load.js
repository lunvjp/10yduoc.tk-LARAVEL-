var countDownDate = new Date().getTime() + 10 * 60 * 1000;

var x = setInterval(function () {
    if (countDownDate) {
        var now = new Date().getTime();
        var distance = countDownDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        $("#time").html(minutes + ":" + seconds);

        if (distance < 0) {
            // clearInterval(x);
            document.getElementById("time").innerHTML = "HẾT GIỜ";
            $("#choiceuser").empty().removeClass('auto-padding');
            setTimeout(function () {
                window.location = '/php/test/mvc2/index.php';
            }, 1000);
        }
    }
}, 1000);

function doTest(id) {
    countDownDate = new Date().getTime() + 10 * 60 * 1000;
    $("#result").empty();
    $("#choiceuser").empty();
    $("#ajax-load").css('display', 'block');
    $("#time").css('display', 'block');
    $.ajax({
        url: 'index.php?controller=index&action=doTest',
        type: 'POST',
        data: {
            id: id // id của bộ test
        },
        success: function (data) {
            $("#ajax-load").css('display', 'none');
            $("#choiceuser").html(data);
        }
    });
}

$(function(){
    $("#submit-button").click(function () {
        $("#form-do-test").submit();
    });

    $(document).on("click", "input", function () {
        id = $(this).attr("class");
        temp = 'div#' + id;
        //                x = 'input'+'.'+id;
        $(temp).css({'pointer-events': 'none', 'background-color': 'antiquewhite'});

        //                setTimeout(function(){
        //                    $(temp).hide();
        //                },200);
    });
});




window.fbAsyncInit = function () {
    FB.init({
        appId: '503740856637847', // FB App ID 503740856637847
        cookie: true,  // enable cookies to allow the server to access the session
        xfbml: true,  // parse social plugins on this page
        version: 'v2.8' // use graph api version 2.8
    });
};

// Load the JavaScript SDK asynchronously
(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Facebook login with JavaScript SDK
function fbLogin() {
    FB.login(function (response) {
        if (response.authResponse) { // Đăng nhập thành công ở ngay đây rồi
            // Get and display the user profile data
            getFbUserData();
        } else {
//                document.getElementById('status').innerHTML = 'User cancelled login or did not fully authorize.';
        }
    }, {scope: 'public_profile,email'});
}

// Fetch the user profile data from facebook
function getFbUserData() {
    FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
        function (response) {
            // Save user data
            saveUserData(response);
            // $("#form-fade").submit();
        });
}

// Logout from facebook
function fbLogout() {
    FB.logout(function () {
        document.getElementById('fbLink').setAttribute("onclick", "fbLogin()");
        document.getElementById('fbLink').innerHTML = '<img src="images/fblogin.png"/>';
        document.getElementById('userData').innerHTML = '';
        document.getElementById('status').innerHTML = 'You have successfully logout from Facebook.';
    });
}

// Save user data to the database
function saveUserData(userData) {
    $.post('index.php?controller=user&action=login',
        {oauth_provider: 'facebook', userData: JSON.stringify(userData)},
        function (data) {
            // $("#account").html(data);
            window.location.replace('/php/test/mvc2/?controller=user&action=login&subject_id=1');
            // return true;
        });
}