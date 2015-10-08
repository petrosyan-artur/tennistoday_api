$(function(){
    $('#keywords').tablesorter();
});

$(document).ready(function () {
    $('#addNewCompany').click(function () {
        var username = $('#username').val();
        var password = $('#password').val();
        var companyName = $('#companyName').val();
        if (username == '') {
            $('#username').addClass("red_border");
            $('#username').tooltip('show', {
                title: "Field can't be empty!"
            });
            return false;
        }
        if (password == '') {
            $('#password').addClass("red_border");
            $('#password').tooltip('show', {
                title: "Field can't be empty!"
            });
            return false;
        }
        if (companyName == '') {
            $('#companyName').addClass("red_border");
            $('#companyName').tooltip('show', {
                title: "Field can't be empty!"
            });
            return false;
        }
        console.log(username, password, companyName);
        $.ajax({
            type: "POST",
            data: {username: username, password: password, companyName: companyName},
            dataType: 'json',
            url: "/app_dev.php/request/addNewCompany",
            success: function (res) {
                console.log(res);
                if (res.success == true) {
                    alert('New company has been added successfully!');
                    window.location.reload();
                } else {
                    alert(res.reason);
                }
            }
        });
    });
});

$(document).ready(function($){
    $("#username").on('focus', function(){
        $('#username').removeClass("red_border");
        $('#username').tooltip('hide');
    });
    $("#password").on('focus', function(){
        $('#password').removeClass("red_border");
        $('#password').tooltip('hide');
    });
    $("#companyName").on('focus', function(){
        $('#companyName').removeClass("red_border");
        $('#companyName').tooltip('hide');
    });
});