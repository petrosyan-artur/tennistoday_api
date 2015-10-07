/**
 * Created by Knyazyan on 9/28/15.
 */
$(document).ready(function(){
    $('.datetime').appendDtpicker({
        "autodateOnStart": false,
        "futureOnly": true
    });
//    $('#stop_date').appendDtpicker({
//        "autodateOnStart": false,
//        "futureOnly": true
//    });
    $(".datetime").on('focus', function(){
        $('#start_date').removeClass("red_border");
        $('#start_date').tooltip('hide');
    });
//    $("#stop_date").on('focus', function(){
//        $('#stop_date').removeClass("red_border");
//        $('#stop_date').tooltip('hide');
//    })
});

openOfferModal = function(id, name) {
    console.log(id, name);
    $('#offerCourtId').text(id);
    $('#offerCourtName').text(name);
    $('#offerModal').modal({show:true});
}

$(document).ready(function($){
    $("#stop_date").on('focus', function(){
        $('#start_date').removeClass("red_border");
        $('#start_date').tooltip('hide');
    });
    $("#stop_date").on('focus', function(){
        $('#stop_date').removeClass("red_border");
        $('#stop_date').tooltip('hide');
    });
    $("#subject").on('focus', function(){
        $('#price').removeClass("red_border");
        $('#subject').tooltip('hide');
    });
});

$(document).ready(function () {
    $('#addCourtOffer').click(function () {
        var id = $('#offerCourtId').text();
        var startDate = $('#start_date').val();
        var stopDate = $('#stop_date').val();
        var price = $('#price').val();
        if (startDate == '') {
            $('#start_date').addClass("red_border");
            $('#start_date').tooltip('show', {
                title: "Field can't be empty!"
            });
            return false;
        }
        if (stopDate == '') {
            $('#stop_date').addClass("red_border");
            $('#stop_date').tooltip('show', {
                title: "Field can't be empty!"
            });
            return false;
        }
        if (price == '' || isNaN(price)) {
            $('#price').addClass("red_border");
            $('#price').tooltip('show', {
                title: "Field can't be empty!"
            });
            return false;
        }
        $.ajax({
            type: "POST",
            data: {id: id, startDate: startDate, stopDate: stopDate, price: price},
            dataType: 'json',
            url: "/app_dev.php/request/addCourtOffer",
            success: function (res) {
                console.log(res);
                if (res.success == true) {
                    $('#start_date').text('');
                    $('#stop_date').text('');
                    $('#price').text('');
                    $('#offerModal').modal('hide');
                    alert('The offer has been added successfully!');
                } else {
                    alert(res.reason);
                }
            }
        });
    });
});

updateCourtOffer = function(id) {

    var startDate = $('#start_date_'+id).val();
    var stopDate = $('#stop_date_'+id).val();
    var price = $('#price_'+id).val();
    var status = $('#status_'+id).val();
    if (startDate == '') {
        $('#start_date_'+id).addClass("red_border");
        $('#start_date_'+id).tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
    if (stopDate == '') {
        $('#stop_date_'+id).addClass("red_border");
        $('#stop_date_'+id).tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
    if (price == '' || isNaN(price)) {
        $('#price_'+id).addClass("red_border");
        $('#price_'+id).tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
    console.log(startDate, stopDate, price, status);
    $.ajax({
        type: "POST",
        data: {id: id, startDate: startDate, stopDate: stopDate, price: price, status: status},
        dataType: 'json',
        url: "/app_dev.php/request/updateCourtOffer",
        success: function (res) {
            console.log(res);
            if (res.success == true) {
                alert('The offer has been updated successfully!');
            } else {
                alert(res.reason);
            }
        }
    });
};

$(function(){
    $('#keywords').tablesorter();
});
