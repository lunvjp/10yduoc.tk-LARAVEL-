// var idSum;


// function seeResult(id) { // id = testid
//     idSum = id;
//     $("#choiceuser-container").css('width','40%');
//     $("#choiceuser").empty().removeClass('auto-padding');
//     $("#ajax-load").css('display','block');
//     $("#time").css('display','none');
//     $.ajax({
//         url: "index.php?controller=user&action=seeResult",
//         type: 'POST',
//         data: {
//             id: id
//         },success: function(data) {
//             $("#ajax-load").css('display','none');
//             $("#choiceuser").html(data);
//         }
//     });
//     // Sau khi load câu hỏi xong thì mới load tiếp facebook bởi vì câu hỏi quan trọng hơn load facebook
//     $.ajax({
//         url: 'index.php?controller=user&action=loadFaceComment',
//         type: 'POST',
//         data: {
//             id: id // test_id
//         },success: function (data) {
//             $("#facebook").html(data);
//         }
//     });
// }


$(function(){


    // $("#wrong-button").click(function () {
    //     $("#choiceuser-container").css('width','40%');
    //     $(".right").fadeOut("1200");
    //     $(".wrong").fadeIn("1200");
    // });
    //
    // $("#right-button").click(function () {
    //     $("#choiceuser-container").css('width','40%');
    //     $(".wrong").fadeOut("1200");
    //     $(".right").fadeIn("1200");
    // });
    //
    // $("#submit-button").click(function(){
    //     $("#form-do-test").submit();
    // });
    //
    // $("#turnoffface-button").click(function(){
    //     $("#facebook").fadeToggle("1200");
    // });

    $(document).on ("click", "input", function () {
        id = $(this).attr("class");
        temp = 'div#'+id;
        $(temp).css({'pointer-events':'none','background-color':'antiquewhite'});
    });
    // Mỗi lần click là gửi 1 ajax, lúc refresh lại l
    // Viết 1 hàm khi click vào thì nó sẽ nhảy ra đáp án.
});
