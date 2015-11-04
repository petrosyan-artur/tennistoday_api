/**
 * Created by Knyazyan on 9/28/15.
 */
var companyName = $('#companyName').text();
var hash = $('#hash').text();

$(document).ready(function(){
    $('.datetime').datetimepicker({
        format:'Y-m-d H:i'
    });
    $('.date').datetimepicker({
        timepicker: false,
        format:'Y-M-d'
    });
    $('.time').datetimepicker({
        datepicker: false,
        format:'H:i',
        step: 10
    });
    $(".date").on('focus', function(){
        $('.date').removeClass("red_border");
        $('.date').tooltip('hide');
    });
    $(".time").on('focus', function(){
        $('.time').removeClass("red_border");
        $('.time').tooltip('hide');
    });
});

//    $('.datetime').appendDtpicker({
//        "autodateOnStart": false,
//        "futureOnly": true
//    });
//    $(".datetime").on('focus', function(){
//        $('#start_date').removeClass("red_border");
//        $('#start_date').tooltip('hide');
//    });
//});

/////////// file upload ///////////

$(function(){

    var ul = $('#upload ul');

    $('#drop a').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').click();
    });

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {

            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name)
                .append('<i>' + formatFileSize(data.files[0].size) + '</i>');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
            updateModalImageList();
        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
            }
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
        }

    });


    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }


    ///refreshing court images in modal
    function updateModalImageList() {
        setTimeout(function(){
            var subCompName = $('#subComp_modal_name').val();
            console.log(subCompName);
            openSubCompImagesModal(1, subCompName);
        }, 1000);
    }
    ///

});

/////////// file upload ///////////

openOfferModal = function(id, name) {
    console.log(id, name);
    $('#offerCourtId').text(id);
    $('#offerCourtName').text(name);
    $('#offerModal').modal({show:true});
}

var getSubCompImagesList = function(name)
{
    $.ajax({
        type: "POST",
        data: {subCompName: name},
        dataType: 'json',
        url: "/app_dev.php/request/getSubCompImages",
        success: function (res) {
            console.log(res);
            name = name.replace(/\s+/g, '');
            if (res.success == true) {
                var data = '<table class="table modal_table">';
                for (var i = 0; i < res.images.length; i++) {
                    data += "<tr><td style='text-align:right;'><img src='/uploads/"+name+"/"+res.images[i]+"' class='modal_image' id='"+res.images[i].slice(0, -4)+"' onmouseover='zoomInImage(\""+res.images[i].slice(0, -4)+"\")' onmouseout='zoomOutImage(\""+res.images[i].slice(0, -4)+"\")'/></td><td style='text-align:left;'><button class='btn btn-danger btn-xs' onclick='removeImage(\""+name+"\",\""+res.images[i]+"\")'>X Remove</button></td></tr>";
                    //Do something
                }
                data += "</table>";
                $('#subCompanyImagesBody').html(data);
                console.log(data);
            } else {
                $('#subCompanyImagesBody').html('');
                console.log(res);
            }
        }
    });
};


var removeImage = function(name, image)
{
    name = name.replace(/\s+/g, '');
    console.log(name, image);
    $.ajax({
        type: "POST",
        data: {subCompName: name, imageName: image},
        dataType: 'json',
        url: "/app_dev.php/request/removeSubCompImage",
        success: function (res) {
            console.log(res);
            if (res.success == true) {
//                var images = JSON.parse(res.images);
//                var imgLength = images.length;
                var data = '<table class="table">';
                for (var i = 0; i < res.images.length; i++) {
                    data += "<tr><td style='text-align:right;'><img src='/uploads/"+name+"/"+res.images[i]+"' class='modal_image' id='"+res.images[i].slice(0, -4)+"' onmouseover='zoomInImage(\""+res.images[i].slice(0, -4)+"\")' onmouseout='zoomOutImage(\""+res.images[i].slice(0, -4)+"\")'/></td><td style='text-align:left;'><button class='btn btn-danger btn-xs' onclick='removeImage(\""+name+"\",\""+res.images[i]+"\")'>X Remove</button></td></tr>";
                    //Do something
                }
                data += "</table>";
                $('#subCompanyImagesBody').html(data);
            } else {
                $('#subCompanyImagesBody').html('');
                console.log(res);
            }
        }
    });
};

var zoomInImage = function(id) {
    $('#'+id).addClass('modal_image_zoom');
}

var zoomOutImage = function(id) {
    $('#'+id).removeClass('modal_image_zoom');
}

openSubCompImagesModal = function(id, name) {
    console.log(id, name);
//    $('#courtImagesBody').text(name);
    $('#subComp_modal_name').val(name);
    getSubCompImagesList(name);
    $('#subCompImages').modal({show:true});
};

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
        $('#subject').removeClass("red_border");
        $('#subject').tooltip('hide');
    });
    $("#company_sub_name_s").on('focus', function(){
        $('#company_sub_name_s').removeClass("red_border");
        $('#company_sub_name_s').tooltip('hide');
    });
    $("#court_name").on('focus', function(){
        $('#coourt_name').removeClass("red_border");
        $('#court_name').tooltip('hide');
    });
});

$(document).ready(function () {
    $('#addCourtOffer').click(function () {
        var id = $('#offerCourtId').text();
        var date = $('#date').val();
        var startDate = $('#start_date').val();
        var stopDate = $('#stop_date').val();
        var price = $('#price').val();
        if (date == '') {
            $('#date').addClass("red_border");
            $('#date').tooltip('show', {
                title: "Field can't be empty!"
            });
            return false;
        }
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
            data: {id: id, startDate: date+' '+startDate, stopDate: date+' '+stopDate, price: price, companyName: companyName, hash: hash},
            dataType: 'json',
            url: "/app_dev.php/request/addCourtOffer",
            success: function (res) {
                console.log(res);
                if (res.success == true) {
                    $('#date').text('');
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

updateCourtOffer = function(id, isAlert) {

    var date = $('#date_'+id).val();
    var startDate = $('#start_date_'+id).val();
    var stopDate = $('#stop_date_'+id).val();
    var price = $('#price_'+id).val();
    var status = $('#status_'+id).val();
    if (date == '') {
        $('#date_'+id).addClass("red_border");
        $('#date_'+id).tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
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
    console.log(date, startDate, stopDate, price, status);
    $.ajax({
        type: "POST",
        data: {id: id, startDate: date+' '+startDate, stopDate: date+' '+stopDate, price: price, status: status, companyName: companyName, hash: hash},
        dataType: 'json',
        url: "/app_dev.php/request/updateCourtOffer",
        success: function (res) {
            console.log(res);
            if (res.success == true) {
                $('#current_status_'+id).html(status);
                if (isAlert == 'yes' ) {
                    alert('The offer has been updated successfully!');
                }
            } else {
                alert(res.reason);
            }
        }
    });
};

updateAllCourtOffers = function(count) {
    var id = null;
    var date = null;
    var stopDate = null;
    var sumDate = null;
    var current_date = new Date();
    console.log(current_date);
//    return;
    for (var i = 1; i < count + 1; i++) {
        date = $('#date_'+id).val();
        stopDate = $('#stop_date_'+id).val();
        sumDate = new Date(date + ' ' + stopDate);
        id = $('#offer_id_'+i).val();
        if (current_date < sumDate) {
            updateCourtOffer(id, 'no');
            console.log(i, id);
        }
    }
    alert('The offers have been updated successfully!')
};

updateCourts = function(id) {

    var companySubName = $('#company_sub_name_'+id).val();
    var surfaceType = $('#surface_type_'+id).val();
    var coverType = $('#cover_type_'+id).val();
    var country = $('#country_iso_code_'+id).val();
    var state = $('#state_'+id).val();
    var city = $('#city_'+id).val();
    var street = $('#street_'+id).val();
    if (state == '') {
        $('#state_'+id).addClass("red_border");
        $('#state_'+id).tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
    if (city == '') {
        $('#city_'+id).addClass("red_border");
        $('#city_'+id).tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
    if (street == '') {
        $('#street_'+id).addClass("red_border");
        $('#street_'+id).tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
    console.log(id, companySubName, surfaceType, coverType, country, state, city, street);

    $.ajax({
        type: "POST",
        data: {id: id, companySubName: companySubName, surfaceType: surfaceType, coverType: coverType, country: country, state: state, city: city, street: street, companyName: companyName, hash: hash},
        dataType: 'json',
        url: "/app_dev.php/request/updateCourts",
        success: function (res) {
            console.log(res);
            if (res.success == true) {
                alert('Court has been updated successfully!');
            } else {
                alert(res.reason);
            }
        }
    });
};

$('#addNewCourt').click(function () {

    var companySubName = $('#company_sub_name').val();
    var courtName = $('#court_name').val();
    var surfaceType = $('#surface_type').val();
    var coverType = $('#cover_type').val();
    var country = $('#country_iso_code').val();
    var state = $('#state').val();
    var city = $('#city').val();
    var street = $('#street').val();

    if (courtName == '') {
        $('#court_name').addClass("red_border");
        $('#court_name').tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }

    console.log(companySubName, courtName, surfaceType, coverType, country, state, city, street);

    $.ajax({
        type: "POST",
        data: {companySubName: companySubName, courtName: courtName, surfaceType: surfaceType, coverType: coverType, country: country, state: state, city: city, street: street, companyName: companyName, hash: hash},
        dataType: 'json',
        url: "/app_dev.php/request/addNewCourt",
        success: function (res) {
            console.log(res);
            if (res.success == true) {
                alert('New court has been added successfully!');
                window.location.reload();
            } else {
                alert(res.reason);
            }
        }
    });
});

$('#addNewSubCompany').click(function () {

    var companySubName = $('#company_sub_name_s').val();
    var country = $('#country_iso_code_s').val();
    var state = $('#state_s').val();
    var city = $('#city_s').val();
    var street = $('#street_s').val();
    var phoneNumber = $('#phone_number').val();
    console.log(companySubName, country, state, city, street, phoneNumber);
    if (companySubName == '') {
        $('#company_sub_name_s').addClass("red_border");
        $('#company_sub_name_s').tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }

    if (state == '') {
        $('#state_s').addClass("red_border");
        $('#state_s').tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
    if (city == '') {
        $('#city_s').addClass("red_border");
        $('#city_s').tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
    if (street == '') {
        $('#street_'+id).addClass("red_border");
        $('#street_'+id).tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }

    if (phoneNumber == '' || isNaN(phoneNumber)) {
        $('#phone_number').addClass("red_border");
        $('#phone_number').tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }

    console.log(companySubName, country, state, city, street, phoneNumber);

    $.ajax({
        type: "POST",
        data: {companySubName: companySubName, country: country, state: state, city: city, street: street, phoneNumber: phoneNumber, companyName:companyName, hash: hash},
        dataType: 'json',
        url: "/app_dev.php/request/addNewSubCompany",
        success: function (res) {
            console.log(res);
            if (res.success == true) {
                alert('New subCompany has been added successfully!');
                window.location.reload();
            } else {
                alert(res.reason);
            }
        }
    });
});

updateSubComp = function(id) {

    var companySubName = $('#sub_company_sub_name_'+id).val();
    var country = $('#sub_country_'+id).val();
    var state = $('#sub_state_'+id).val();
    var city = $('#sub_city_'+id).val();
    var street = $('#sub_street_'+id).val();
    var phoneNumber = $('#sub_phone_number_'+id).val();
    if (state == '') {
        $('#sub_state_'+id).addClass("red_border");
        $('#sub_state_'+id).tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
    if (city == '') {
        $('#sub_city_'+id).addClass("red_border");
        $('#sub_city_'+id).tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
    if (street == '') {
        $('#sub_street_'+id).addClass("red_border");
        $('#sub_street_'+id).tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
    if (phoneNumber == '') {
        $('#sub_phone_number_'+id).addClass("red_border");
        $('#sub_phone_number_'+id).tooltip('show', {
            title: "Field can't be empty!"
        });
        return false;
    }
    console.log(id, companySubName, country, state, city, street, phoneNumber);

    $.ajax({
        type: "POST",
        data: {id: id, companySubName: companySubName, country: country, state: state, city: city, street: street, companyName: companyName, phoneNumber: phoneNumber, hash: hash},
        dataType: 'json',
        url: "/app_dev.php/request/updateSubCompany",
        success: function (res) {
            console.log(res);
            if (res.success == true) {
                alert('SubCompany has been updated successfully!');
            } else {
                alert(res.reason);
            }
        }
    });
};

$(function(){
    $('#keywords').tablesorter();
});
