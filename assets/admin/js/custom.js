/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 * 
 */
"use strict";

$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});


//image choose and show
var loadFile = function(event) {
    var image = document.getElementById('selected_img');
    image.src = URL.createObjectURL(event.target.files[0]);
};

//input text validation  - for name - only letters and sapce can accept ---START--
$(".name_validation").keyup(function(event) {
    /*var inputValue = event.charCode;
    if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)){
        event.preventDefault();
    }*/
    var raw_text = jQuery(this).val();
    var return_text = raw_text.replace(/[^a-zA-Z ]/g, ''); //after z space
    jQuery(this).val(return_text);
});
//input text validation  - for name - only letters and sapce can accept ---END--

//text accept only letters/numbers/space----START----
/* $(".text_with_number_validation").keyup(function(event){
    var raw_text =  jQuery(this).val();
    var return_text = raw_text.replace(/[^a-zA-Z0-9 ]/g,'');//after nine space
    jQuery(this).val(return_text);
 });*/
//text accept only letters/numbers/space----END----

jQuery('.amharic_name_validation').on('keydown', function(e) {
    /*var charCode = e.key.charCodeAt(0);
    if (!((charCode >= 0x30 && charCode <= 0x39) || (charCode >= 0x41 && charCode <= 0x7a))) {
        e.preventDefault();
    }*/
    var yourInput = $(this).val();
    var re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
    var isSplChar = re.test(yourInput);
    if (isSplChar) {
        var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $(this).val(no_spl_char);
    }
});


/*$('input').on('keyup', function(event){
    if (event.keyCode < 48 || event.keyCode > 57)
        return false;
});*/

// Below function does not allow to enter space at first position --START--
$(".check_space").keydown(function(e) {
    if (e.which === 32 && e.target.selectionStart === 0) {
        return false;
    }
});
// Below function does not allow to enter space at first position --END--

// # UT : Contact number should work for only numbers and backspace --START---
$(".contact_number").keypress(function(e) {

    var charCode = (e.which) ? e.which : event.keyCode;
    if (charCode != 45 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }

});


// Contact number should work for only numbers and backspace --END---

// only text can accept number-------START---
$(".number_foramte").keypress(function(e) {
    var charCode = (e.which) ? e.which : event.keyCode

    if (String.fromCharCode(charCode).match(/[^0-9]/g))

        return false;
});
// only text can accept number-------END---

$('.number_float').keypress(function(event) {
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
});

//validate postal code input
$(".user_age").keypress(function(e) {
    var charCode = (e.which) ? e.which : event.keyCode

    if (String.fromCharCode(charCode).match(/[^0-9]/g))

        return false;
});

var is_user_age_valid = true;
$(".user_age_valid").keyup(function(e) {
    var user_age = $(this).val();
    if (user_age > 100) {
        is_user_age_valid = false;
        $('.user_age_valid').css('border-color', 'red');
        $('#age_error').text('Please enter valid age');
    } else {
        is_user_age_valid = true;
        $('.user_age_valid').css('border-color', '#ccc');
        $('#age_error').text('');
    }
});

// filter button toggle custom js 
function filtertoggle() {
    var x = document.getElementById("filter-togle-section");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}

$(document).ready(function() {

    var is_mobile_no_valid = true;
    $(".check_mobile_number_valid").keyup(function(e) {
        var mobile = $(this).val();
        $(this).css('border-color', '#ccc');
        $('#mobile_no_error').text('');
        if (mobile == '0000000000') {
            $('.check_mobile_number_valid').css('border-color', 'red');
            $('#mobile_no_error').text('Please enter valid number');
            is_mobile_no_valid = false;
        }else{
            is_mobile_no_valid = true;
        }
    });

    // sucess  and error alert  hide after  some second 
    setTimeout(function() {
        $(".close_alert").trigger('click');
    }, 8000);

    /*New Password and confirm password match validation ------START-----*/

    var password_vaidate_status = true;

    function comman_function_for_check_confrim_password(np_val, cnp_val) {

        var np_len = np_val.length;
        var cnp_len = cnp_val.length;
        /*if(np_len > 12 || cnp_len > 12 || np_len < 6 || cnp_len < 6)
        {*/
        if (np_len > 12 || np_len < 6) {

            $(".np_password").removeClass('valid_input_data');
            $(".np_password").css('border-color', 'red');
            $("#np_password_error").html('Password length must be in between 6 to 12 characters');
            return 2;
        } else if (np_val != cnp_val && cnp_val != "") {

            $(".cnp_password").css('border-color', 'red');
            $(".cnp_password").removeClass('valid_input_data');
            $("#cnp_password_error").html('New password and confirm new password does not match');
            return 3;
        } else if (np_val.match(/[A-Z]/) && np_val.match(/\d/) && np_val.match(/[A-z]/) && np_val.match(/[~!,@#%&_\$\^\*\?\-]/)) {

            $(".np_password").addClass('valid_input_data');
            $("#np_password_error").html('');

            if (cnp_val != "") {
                $(".cnp_password").addClass('valid_input_data');
                $("#cnp_password_error").html('');
            }

            return 1;

        } else {
            $(".cnp_password").removeClass('valid_input_data');
            $(".np_password").css('border-color', 'red');
            $("#np_password_error").html('Password must include alphanumeric, special characters and capital letters');
            return 4;
        }
    }


    $(".np_password, .cnp_password").keyup(function(e) {
        var np_val = $(".np_password").val();
        var cnp_val = $(".cnp_password").val();

        if (np_val == cnp_val) {
            $(".cnp_password").addClass('valid_input_data');
            $("#cnp_password_error").html('');
        }
        if (cnp_val == "") {
            $(".cnp_password").css('border-color', '');
            $(".cnp_password").removeClass('valid_input_data');
            $("#cnp_password_error").html('');
        }

        var pwd_final_status = comman_function_for_check_confrim_password(np_val, cnp_val);

        if (np_val === "") {
            $("#np_password_error").html('');
        }

        if (pwd_final_status > 1) {
            password_vaidate_status = false;

        } else {
            $(".np_password").css('border-color', '#e4e6fc');
            $("#np_password_error").html('');
            password_vaidate_status = true;
        }
    });
    /*New Password and confirm password match validation ------END-----*/

    /*Match old Password with existing password -------START---------*/
    $("#old_password").keydown(function(e) {
        var old_password = $(this).val();
        if (e.keyCode === 13) {
            $('#MatchOldPasswordSubmit').trigger("click");
        }
    });
    $("#old_password").keyup(function(e) {
        var old_password = $(this).val();
        var old_password_length = old_password.length;

        if (old_password === "" || old_password_length > 1) {
            $(".old_password").css('border-color', '#e4e6fc');
            $("#old_password_error").html('');
        }
    });
    $('body').on('click', '#MatchOldPasswordSubmit', function() {
        var old_password = $('#old_password').val();

        if (old_password != "") {
            //ajax-------- start-------------
            $.ajax({
                url: BASE_URL + 'ajax/CheckOldPassword/',
                data: {
                    old_password: old_password,
                },
                type: 'post',
                success: function(response) {
                    if (response == 1) {
                        $(".old_password").css('border-color', 'green');
                        $("#old_password_error").html('');
                        $("#old_password_success").html('Please wait while we are processing your request...');
                        setTimeout(function() {
                            $("#hide_after_match_old_pwd").addClass('d-none');
                            $('#ChangePasswordSubmit').removeClass('d-none');
                        }, 2000);

                    } else if (response == 2) { //if session expire
                        window.location.replace(BASE_URL + 'admin/');
                    } else {
                        $(".old_password").css('border-color', 'red');
                        $("#old_password_success").html('');
                        $("#old_password_error").html('Incorrect Password');
                    }
                },

            });
            //ajax-------- end-------------
        } else {
            $(".old_password").css('border-color', 'red');
            $("#old_password_success").html('');
            $("#old_password_error").html('Please enter your old password');
        }
    });
    /*Match old Password with existing password -------END---------*/

    //Update Password  after match old passwoord -----START------
    $("#ChangePasswordSubmit").submit(function(event) {

        var old_password = $('#old_password').val();
        var new_password = $('#new_password').val();
        var confirm_password = $('#confirm_password').val();
        //alert(password_vaidate_status);//should be true

        if (confirm_password == "") {
            $(".cnp_password").removeClass('valid_input_data');
        }


        if (old_password != "" && new_password != "" && confirm_password != "" && password_vaidate_status == true) {
            //ready for submit
            $("#confirm_password_success").html('Please wait while we are processing your request...');
            return;
        } else {
            //alert('invalid');
        }

        event.preventDefault();
    });
    //Update Password  after match old passwoord -----END------

    //Change Status of Service Category ------------START---------
    $('body').on('click', '.cat_status_change', function() {
        var new_service_table_url = service_cat_table_url.replace("table", "1"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case
        var cat_status = $(this).attr('status');
        var cat_id = $(this).attr('cat_id');

        //ajax-------- start-------------
        $.ajax({
            url: BASE_URL + 'ajax/UpdateCategoryStatus/',
            data: {
                cat_status: cat_status,
                cat_id: cat_id
            },
            type: 'post',
            success: function(response) {
                if (response == 1) {
                    $("#category_list").load(new_service_table_url);
                } else if (response == 2) { //if session expire
                    window.location.replace(BASE_URL + 'admin/');
                } else {
                    swal('Oops...', 'Something Went wrong!', 'error');
                }
            },
        });
        //ajax-------- end-------------
    });
    //Change Status of Service Category ------------END----------

    //Change Status as delete of Service Category --------START--------
    $('body').on('click', '.cat_delete', function() {
        var new_service_table_url = service_cat_table_url.replace("table", "2"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case
        var cat_id = $(this).attr('cat_id');
        swal({
                title: "Are you sure to delete this Category permanently?",
                text: "Once deleted, You will lose all relevant services and service providers skill data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    //ajax-------- start-------------
                    $.ajax({
                        url: BASE_URL + 'ajax/DeleteCategoryStatus/',
                        data: {
                            cat_id: cat_id
                        },
                        type: 'post',
                        success: function(response) {
                            if (response == 1) {
                                swal("Category deleted successfully !", {
                                    icon: "success",
                                });

                                setTimeout(function() {
                                    $("#category_list").load(new_service_table_url);
                                }, 2000);

                            } else if (response == 2) { //if session expire
                                window.location.replace(BASE_URL + 'admin/');
                            } else {
                                swal('Oops...', 'Something Went wrong!', 'error');
                            }
                        },
                    });
                    //ajax-------- end-------------

                } else {
                    //swal("Your imaginary file is safe!");
                }
            });
    });
    //Change Status as delete of Service Category ------------END----------

    //category modal data change by mode (add /edit) ----START------------
    $('body').on('click', '.add_edit_category', function() {
        var mode = $(this).attr('mode'); //1 = add , 2 - edit

        $('#add_edit_ServiceCategory').find("input").val('').end();
        $('#selected_img').attr('src', IMG_PATH + 'example-image.jpg');
        if (mode == 1) {
            $('.modal_title_name').text('Add Service Category');
            $('#form_mode').val(1);
        } else if (mode == 2) {

            $('.modal_title_name').text('Edit Service Category');
            $('#form_mode').val(2);

            // we need to get edit data according to category id ----START----
            var cat_id = $(this).attr('cat_id');
            $('#edit_cat_id').val(cat_id);
            $.ajax({
                url: BASE_URL + 'ajax/GET_Category_Data/', //merchant detail
                data: {
                    cat_id: cat_id,
                },
                type: 'post',
                success: function(response) {
                    if (response != 0) {
                        var data = JSON.parse(response);
                        $('#cat_name_english').val(data.cat_name_english);
                        $('#cat_name_amharic').val(data.cat_name_amharic);
                        $('#selected_img').attr('src', IMG_UPLOAD_PATH + data.category_image);
                        $('#exist_category_image').val(data.category_image);
                        $('#file').removeAttr('required');
                    }
                },

            });
            // we need to get edit data according to category id ----START----
        }
    });
    //category modal data change by mode (add /edit) ----END------------

    //Service add/edit - open close time validation ------START-------
    var open_close_time_validate_status = true;
    $('body').on('change', '#open_time', function() {
        //Open time less then close time
        // close time greater then open time
        var open_time = $(this).val();
        var close_time = $('#close_time').val();
        if (open_time > close_time) {
            open_close_time_validate_status = false;
            $('#open_time').css('border-color', 'red');
            $('#open_time_error').text('Open time should be smaller');
        } else {
            open_close_time_validate_status = true;
            $('#close_time').css('border-color', '#ccc');
            $('#close_time_error').text('');

            $('#open_time').css('border-color', '#ccc');
            $('#open_time_error').text('');
        }
    });
    $('body').on('change', '#close_time', function() {
        //Open time less then close time
        // close time greater then open time
        var close_time = $(this).val();
        var open_time = $('#open_time').val();

        if (close_time < open_time) {
            open_close_time_validate_status = false;
            $('#close_time').css('border-color', 'red');
            $('#close_time_error').text('Close time should be Greater');
        } else {
            open_close_time_validate_status = true;
            $('#open_time').css('border-color', '#ccc');
            $('#open_time_error').text('');

            $('#close_time').css('border-color', '#ccc');
            $('#close_time_error').text('');
        }
    });
    //Service add/edit - open close time validation ------END--------

    //Service Form submition----------------START--------------------
    $("#ServiceFormSubmit").submit(function(event) {

        if (open_close_time_validate_status == true) {
            $('#open_time_error').text('');
            $('#close_time_error').text('');
            //ready for submit
            return;
        } else {
            //alert('invalid');
        }

        event.preventDefault();
    });
    //Service Form submition----------------START--------------------

    //change modal mode of service (add /edit) ---------START----------
    $('body').on('click', '.add_edit_Service_mode', function() {
        var mode = $(this).attr('mode'); //1 = add , 2 - edit
        var default_commision = $('#commision').val();

        //clear/empty input fields 
        $('#add_edit_Service').find("input,input[type=file],select").val('').end().find('textarea').text('').end();
        $('#selected_img').attr('src', IMG_PATH + 'example-image.jpg');
        $('#selected_mobile_banner_img').attr('src', IMG_PATH + 'example-image.jpg');
        if (mode == 1) {

            $('#commision').val(default_commision);

            $('.service_modal_title').text('Add Service');
            $('#service_form_mode').val(1);

            $('#file').attr('required', 'required');
        } else if (mode == 2) {
            $('.service_modal_title').text('Edit Service');
            $('#service_form_mode').val(2);

            //geting service data for edit -------------START-------
            var service_id = $(this).attr('service_id');
            $('#edit_service_id').val(service_id);
            $.ajax({
                url: BASE_URL + 'ajax/GET_Service_Data/', //service detail
                data: {
                    service_id: service_id,
                },
                type: 'post',
                success: function(response) {
                    if (response != 0) {
                        var data = JSON.parse(response);
                        $('#service_name_english').val(data.service_name_english);
                        $('#service_name_amharic').val(data.service_name_amharic);

                        $("#select_category_id option[value=" + data.category_id + "]").prop("selected", true);
                        $('#commision').val(data.commision);
                        $('#open_time').val(data.open_time);
                        $('#close_time').val(data.close_time);
                        $("#selected_price_type option[value=" + data.service_price_type + "]").prop("selected", true);
                        $('#service_price').val(data.service_price);
                        $('#visiting_price').val(data.visiting_price);
                        $('#service_note').val(data.service_note);
                        $('#service_description_english').text(data.service_description_english);
                        $('#service_description_amharic').text(data.service_description_amharic);

                        // service icon image
                        $('#selected_img').attr('src', IMG_UPLOAD_PATH + data.service_image);
                        $('#exist_service_image').val(data.service_image);
                        $('#file').removeAttr('required');

                        //service mobile banner image
                        $('#selected_mobile_banner_img').attr('src', IMG_UPLOAD_PATH + data.service_mobile_banner);
                        $('#exist_service_mobile_banner_image').val(data.service_mobile_banner);
                        $('#file2').removeAttr('required');

                    }
                },

            });
            //geting service data for edit -------------END--------
        }
    });
    //change modal mode of service (add /edit) ---------END----------

    //Change status(Enable/disable) of service(Sub category)-----------START------
    $('body').on('click', '.service_status_change', function() {

        var new_service_table_url = service_table_url.replace("table", "1"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var service_status = $(this).attr('status');
        var service_id = $(this).attr('service_id');

        //ajax-------- start-------------
        $.ajax({
            url: BASE_URL + 'ajax/UpdateServiceStatus/',
            data: {
                service_status: service_status,
                service_id: service_id
            },
            type: 'post',
            success: function(response) {
                if (response == 1) {
                    $("#service_table").load(new_service_table_url);
                } else if (response == 2) { //if session expire
                    window.location.replace(BASE_URL + 'admin/');
                } else {
                    swal('Oops...', 'Something Went wrong!', 'error');
                }
            },
        });
        //ajax-------- end-------------
    });
    //Change status(Enable/disable) of service(Sub category) ----------END--------

    //Change Status as delete of Service Category --------START--------
    $('body').on('click', '.service_delete', function() {

        var new_service_table_url = service_table_url.replace("table", "2"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var service_id = $(this).attr('service_id');
        swal({
                title: "Are you sure to delete this Service permanently?",
                text: "Once deleted, You will lose all relevant service providers skill data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    //ajax-------- start-------------
                    $.ajax({
                        url: BASE_URL + 'ajax/DeleteServiceStatus/',
                        data: {
                            service_id: service_id
                        },
                        type: 'post',
                        success: function(response) {
                            if (response == 1) {
                                swal("Service deleted successfully !", {
                                    icon: "success",
                                });

                                setTimeout(function() {
                                    $("#service_table").load(new_service_table_url);
                                }, 2000);

                            } else if (response == 2) { //if session expire
                                window.location.replace(BASE_URL + 'admin/');
                            } else {
                                swal('Oops...', 'Something Went wrong!', 'error');
                            }
                        },
                    });
                    //ajax-------- end-------------

                } else {
                    //swal("Your imaginary file is safe!");
                }
            });
    });
    //Change Status as delete of Service Category ------------END----------

    //Search Service (Sub category) ---------------START------------------
    $(".search_key").keydown(function(e) {
        if (e.keyCode === 13) {
            $('#search_services').trigger("click");
        }
    });
    $('body').on('click', '#search_services', function() {
        var from_date = $('#fromdate').val();
        var to_date = $('#todate').val();
        var service_price_type = $('#service_price_type').val();
        var service_category_id = $('#service_category_id').val();
        var service_status = $('#service_status').val();
        var search = $('.search_key').val();

        if (from_date == '') {
            from_date = "all";
        }
        if (to_date == '') {
            to_date = "all";
        }
        if (service_price_type == '') {
            service_price_type = "all";
        }
        if (service_category_id == '') {
            service_category_id = "all";
        }

        if (service_status == '') {
            service_status = "all";
        }

        if (search == '') {
            search = "all";
        } else {

            search = search.trim();
        }

        //for price with currency search
        var country_code_inc = search.includes("Birr");
        if (country_code_inc == true) {
            search = search.replace("Birr", "").trim();
        }

        //for commision search with %
        var country_code_inc = search.includes("%");
        if (country_code_inc == true) {
            search = search.replace("%", "").trim();
        }

        var null_value = search.includes("N/A");
        if (null_value == true) {
            search = search.replace("N/A", "").trim();
        }

        //remove special charcter which is given error by url (means not accepted in url)
        search = remove_special_character_from_entred_search_value(search);
        if (search == '') {
            swal('Oops...', 'Enterd search value is not allowed!', 'warning');
        } else if (from_date != "all" || to_date != "all" || service_price_type != "all" || service_category_id != "" || search != "all") {
            window.location.replace(BASE_URL + 'admin/service_management/0/' + from_date + '/' + to_date + '/' + service_price_type + '/' + service_category_id + '/' + service_status + '/' + search + '/'); //load only table
        }
    });
    //Search Service (Sub category) ---------------END------------------

    function remove_special_character_from_entred_search_value(search_value) {
        var re = /[`~!#%^&*()|+\=?;'"<>\{\}\[\]\\\/]/gi;
        var isSplChar = re.test(search_value);
        if (isSplChar) {
            var search_value = search_value.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        }
        return search_value;
    }

    //Search Service Category----------------START---------------------
    $(".search_key").keydown(function(e) {
        if (e.keyCode === 13) {
            $('#search_service_cat').trigger("click");
        }
    });
    $('body').on('click', '#search_service_cat', function() {
        var from_date = $('#fromdate').val();
        var to_date = $('#todate').val();
        var cat_status = $('#cat_status').val();
        var search = $('.search_key').val();

        if (from_date == '') {
            from_date = "all";
        }
        if (to_date == '') {
            to_date = "all";
        }

        if (cat_status == '') {
            cat_status = "all";
        }

        if (search == '') {
            search = "all";
        } else {
            search = search.trim();
        }

        var null_value = search.includes("N/A");
        if (null_value == true) {
            search = search.replace("N/A", "").trim();
        }

        //remove special charcter which is given error by url (means not accepted in url)
        search = remove_special_character_from_entred_search_value(search);
        if (search == '') {
            swal('Oops...', 'Enterd search value is not allowed!', 'warning');
        } else if (from_date != "all" || to_date != "all" || cat_status != "all" || search != "all") {
            window.location.replace(BASE_URL + 'admin/service_categories/0/' + from_date + '/' + to_date + '/' + cat_status + '/' + search + '/'); //load only table

        }
    });
    //Search Service Category----------------END-----------------------
    $('body').on("click",".submit_form", function(){             
         $('.form-control').removeClass('remove_error_input_border');
         
        $('.invalid-feedback').removeClass('remove_error_input_texterror');
    });

    $('body').on("click",".modal_click,.close", function(){             
      
        
        $('.invalid-feedback').addClass('remove_error_input_texterror');
        $('.input_error').text('');
        $('.form-control').addClass('remove_error_input_border');
        
        //for setting page only
        $('#check_if_exist_city_name').val('');
        $('#check_if_exist_city_name').removeAttr('style');
    });

    //Change the mode of customer modal for add/edit--------------START--------
    $('body').on('click', '.add_edit_customer_mode', function() {
        var mode = $(this).attr('mode'); //1 = add , 2 - edit
       
        $('#add_edit_Customer_Modal').find("input,input[type=file],select").val('').end();
        $('#check_mobile_if_exist').val('false');
        $('#check_email_if_exist').val('false');
        $('.check_email_if_exist').css('border-color', '#28a745');
        $('#email_error').text('');
        $('.check_contact_number_exist').css('border-color', '#ccc');
        $('#mobile_no_error').text('');
        if (mode == 1) { //add
            $('.customer_modal_title').text('Add Customer');
            $('#customer_form_mode').val(1);

            $('#customer_mobile').removeAttr('readonly');
            $('#selected_img').attr('src', IMG_PATH + 'avatar/avatar-1.png');
        } else if (mode == 2) {
            $('.customer_modal_title').text('Edit Customer');
            $('#customer_form_mode').val(2);

            //mobile number will be editable same as for api if
            //mobile will not update from the admin controller
            $('#customer_mobile').attr('readonly', '');

            //geting customer data for edit -------------START-------
            var user_id = $(this).attr('user_id');
            $('#edit_customer_id').val(user_id);
            $.ajax({
                url: BASE_URL + 'ajax/GET_User_Data/', //customer detail
                data: {
                    user_id: user_id,
                },
                type: 'post',
                success: function(response) {
                    if (response != 0) {
                        var data = JSON.parse(response);
                        $('#customer_fullname').val(data.fullname);
                        $('#customer_email').val(data.email);
                        $('#customer_mobile').val(data.mobile);

                        if (data.age == 0) {
                            var age = "";
                        } else {
                            var age = data.age;
                        }

                        $('#age').val(age);

                        $("#gender option[value='" + data.gender + "']").prop("selected", true);

                        if (data.profile_image != "") {
                            $('#selected_img').attr('src', IMG_UPLOAD_PATH + data.profile_image);
                        } else {
                            $('#selected_img').attr('src', IMG_PATH + 'avatar/avatar-1.png');
                        }

                        $('#exist_customer_image').val(data.profile_image);
                        $('#file').removeAttr('required');
                    }
                },

            });
            //geting customer data for edit -------------END--------
        }
    });
    //Change the mode of customer modal for add/edit--------------END----------

    //Search Customer----------------START---------------------
    $(".search_key").keydown(function(e) {
        if (e.keyCode === 13) {
            $('#search_customer').trigger("click");
        }
    });
    $('body').on('click', '#search_customer', function() {

        var from_date = $('#fromdate').val();
        var to_date = $('#todate').val();
        var user_status = $('#user_status').val(); //(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
        var gender = $('#gender').val(); //1- male, 2- female, 3- other
        var search = $('.search_key').val();

        if (from_date == '') {
            from_date = "all";
        }
        if (to_date == '') {
            to_date = "all";
        }

        if (user_status == '') {
            user_status = "all";
        }

        if (gender == '') {
            gender = "all";
        }

        if (search == '') {
            search = "all";
        } else {

            search = search.trim();
        }

        //for phone number search
        var country_code_inc = search.includes("+251");
        if (country_code_inc == true) {
            search = search.replace("+251", "").trim();
        }

        var null_value = search.includes("N/A");
        if (null_value == true) {
            search = search.replace("N/A", "").trim();
        }

        //remove special charcter which is given error by url (means not accepted in url)
        search = remove_special_character_from_entred_search_value(search);
        if (search == '') {
            swal('Oops...', 'Enterd search value is not allowed!', 'warning');
        } else if (from_date != "all" || to_date != "all" || user_status != "all" || gender != "all" || search != "all") {
            window.location.replace(BASE_URL + 'admin/customer_management/0/' + from_date + '/' + to_date + '/' + user_status + '/' + gender + '/' + search + '/'); //load only table
        }
    });
    //Search Customer----------------END-----------------------

    // Update Customer status ----------------START---------------
    $('body').on('click', '.customer_status_change', function() {

        var new_service_table_url = customer_table_url.replace("table", "1"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var customer_status = $(this).attr('status');
        var user_id = $(this).attr('user_id');
        //ajax-------- start-------------
        $.ajax({
            url: BASE_URL + 'ajax/UpdateUserStatus/',
            data: {
                user_status: customer_status,
                user_id: user_id
            },
            type: 'post',
            success: function(response) {
                if (response == 1) {
                    $("#customer_table").load(new_service_table_url);
                } else if (response == 2) { //if session expire
                    window.location.replace(BASE_URL + 'admin/');
                } else {
                    swal('Oops...', 'Something Went wrong!', 'error');
                }
            },
        });
        //ajax-------- end-------------
    });
    // Update Customer status ----------------END----------------

    //Change Status as delete of Customer --------START--------
    $('body').on('click', '.customer_delete', function() {

        var new_service_table_url = customer_table_url.replace("table", "2"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var user_id = $(this).attr('user_id');
        swal({
                title: "Are you sure to delete this Customer permanently?",
                text: "Once deleted, you will not be able to recover it!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    //ajax-------- start-------------
                    $.ajax({
                        url: BASE_URL + 'ajax/DeleteUserStatus/',
                        data: {
                            user_id: user_id
                        },
                        type: 'post',
                        success: function(response) {
                            if (response == 1) {
                                swal("Customer deleted successfully !", {
                                    icon: "success",
                                });

                                setTimeout(function() {
                                    $("#customer_table").load(new_service_table_url);
                                }, 2000);

                            } else if (response == 2) { //if session expire
                                window.location.replace(BASE_URL + 'admin/');
                            } else {
                                swal('Oops...', 'Something Went wrong!', 'error');
                            }
                        },
                    });
                    //ajax-------- end-------------
                } else {
                    //swal("Your imaginary file is safe!");
                }
            });
    });
    //Change Status as delete of Customer ------------END----------


    //Search Service Provider ----------------START---------------------
    $(".search_key").keydown(function(e) {
        if (e.keyCode === 13) {
            $('#search_service_provider').trigger("click");
        }
    });
    $('body').on('click', '#search_service_provider', function() {

        var from_date = $('#fromdate').val();
        var to_date = $('#todate').val();
        var user_status = $('#user_status').val(); //(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
        var service_provider_rating = $('#service_provider_rating').val(); //1- male, 2- female, 3- other
        var search = $('.search_key').val();

        if (from_date == '') {
            from_date = "all";
        }
        if (to_date == '') {
            to_date = "all";
        }

        if (user_status == '') {
            user_status = "all";
        }

        if (service_provider_rating == '') {
            service_provider_rating = "all";
        }

        if (search == '') {
            search = "all";
        } else {

            search = search.trim();
        }

        //for phone number search
        var country_code_inc = search.includes("+251");
        if (country_code_inc == true) {
            search = search.replace("+251", "").trim();
        }

        var null_value = search.includes("N/A");
        if (null_value == true) {
            search = search.replace("N/A", "").trim();
        }

        //remove special charcter which is given error by url (means not accepted in url)
        search = remove_special_character_from_entred_search_value(search);
        if (search == '') {
            swal('Oops...', 'Enterd search value is not allowed!', 'warning');
        } else if (from_date != "all" || to_date != "all" || user_status != "all" || service_provider_rating != "all" || search != "all") {
            window.location.replace(BASE_URL + 'admin/service_provider_management/0/' + from_date + '/' + to_date + '/' + user_status + '/' + service_provider_rating + '/' + search + '/'); //load only table
        }

    });
    //Search Service Provider----------------END-----------------------

    //comman function for getting service according to category id (for add / edit case)----START------
    function comman_function_for_getting_service(cat_id, service_id, append_id) {
        //ajax  --------start--------
        $.ajax({
            url: BASE_URL + 'ajax/GET_Service_Data_By_Cat/', //service detail
            data: {
                cat_id: cat_id,
            },
            type: 'post',
            success: function(response) {
                if (response != 0) {
                    var json = JSON.parse(response);
                    var count = 1;
                    var selectbox;

                    $.each(json, function(index, json) {

                        if (json.service_id == service_id) {
                            var selected = "selected";
                        } else {
                            var selected = "";
                        }
                        
                        selectbox = $('#service_list_' + append_id).append("<option value=" + json.service_id + " " + selected + ">" + json.service_name_english + "</option>");
                        count++;
                    });
                    return selectbox;
                }
            },
        });
        //ajax ------end--------
    }
    //comman function for getting service according to category id (for add / edit case)----END------

    //comman function for append service and skills inputs (for add/edit case)--start---
    function comman_function_append_skills_input(count) {

        var category_html = $('#category_list_0').html(); //first select box
        
        $('.append_skill_input').append('<div class="append_skill_input_div">' +
            '<div class="row">' +
            '<div class="col-sm-6">' +
            '<div class="form-group">' +
            ' <label>Service Category</label>' +
            ' <select class="form-control category_list_for_skills" required="" name="service[' + count + '][cat_id]" id="category_list_' + count + '" append_id="' + count + '">' +
            category_html + '' +
            '</select>' +
            '<div class="invalid-feedback">Please select category' +
            '</div>' +
            '</div>' +
            '</div>'

            +
            '<div class="col-sm-6">' +
            ' <div class="form-group">' +
            ' <label>Service</label>' +
            ' <select class="form-control service_list"  required="" name="service[' + count + '][service_id]" append_id="' + count + '" id="service_list_' + count + '">' +
            '<option value="">Select Service</option>' +
            ' </select>' +
            ' <div class="invalid-feedback">Please select service' +
            ' </div>' +
            '</div>' +
            ' </div>' +
            '</div>'

            +
            ' <div class="form-group">' +
            '<label>Skills (in english)</label>' +
            '<input type="text" placeholder="Enter Provider Skills"  class="form-control check_space text_with_number_validation" id="service_provider_skill_english_' + count + '" name="service[' + count + '][skill_english]" required="">' +
            ' <div class="invalid-feedback">Please enter skill in english' +
            '</div>' +
            '<label>Skills (in amharic) <a href="javascript:void(0)" class="btn btn-danger remove_append_skill_button"><i class="fa fa-trash"></i> </a></label>' +
            '<input type="text" placeholder="Enter Provider Skills"  class="form-control check_space amharic_name_validation" id="service_provider_skill_amharic_' + count + '" name="service[' + count + '][skill_amharic]" required="">' +
            ' <div class="invalid-feedback">Please enter skill in amharic' +
            '</div>' +
            '</div>' +
            '</div>');
        
    }
    //comman function for append service and skills inputs (for add/edit case)--end---

    //Change the mode of service provider modal for add/edit----------START--------
    $('body').on('click', '.add_edit_service_provider_mode', function() {
        var mode = $(this).attr('mode'); //1 = add , 2 - edit
        $('.check_contact_number_exist').css('border-color', '#ccc');
        $('#mobile_no_error').text('');
        //clear/empty input fields 
        $('#add_edit_ServiceProvider').find("input,input[type=file],select").val('').end().find('textarea').text('').end();
        $('#selected_img').attr('src', IMG_PATH + 'avatar/avatar-1.png');
        $('#check_mobile_if_exist').val('false');
        $('#check_email_if_exist').val('false');
        $('.check_email_if_exist').css('border-color', '#28a745');
        $('#email_error').text('');
        if (mode == 1) { //add
            $('.service_provider_modal_title').text('Add Service Provider');
            $('#service_provider_form_mode').val(1);

            $('#service_provider_mobile').removeAttr('readonly');
        } else if (mode == 2) {
            $('.service_provider_modal_title').text('Edit Service Provider');
            $('#service_provider_form_mode').val(2);

            //mobile number will be editable same as for api if
            //mobile will not update from the admin controller
            $('#service_provider_mobile').attr('readonly', '');

            //geting customer data for edit -------------START-------
            var user_id = $(this).attr('user_id');
            $('#edit_service_provider_id').val(user_id);
            $.ajax({
                url: BASE_URL + 'ajax/GET_ServiceProvider_Data/', //merchant detail
                data: {
                    user_id: user_id,
                },
                type: 'post',
                success: function(response) {
                    if (response != 0) {
                        var data = JSON.parse(response);
                        $('#service_provider_fullname').val(data.fullname);
                        $('#service_provider_email').val(data.email);
                        $('#service_provider_mobile').val(data.mobile);

                        if (data.age == 0) {
                            var age = "";
                        } else {
                            var age = data.age;
                        }
                        $('#service_provider_age').val(age);
                        $('#provider_location').val(data.google_map_pin_address);
                        $('#provider_latitude').val(data.latitude);
                        $('#provider_longitude').val(data.longitude);
                        $('#service_provider_note').text(data.note);

                        $("#service_provider_gender option[value='" + data.gender + "']").prop("selected", true);

                        if (data.profile_image != "") {
                            $('#selected_img').attr('src', IMG_UPLOAD_PATH + data.profile_image);
                        } else {
                            $('#selected_img').attr('src', IMG_PATH + 'avatar/avatar-1.png');
                        }

                        $('#exist_service_provider_image').val(data.profile_image);
                        $('#file').removeAttr('required'); 

                        //console.log(data.skills);
                        //we are getting skills in array---------start-------
                        var skills_total_record = data.skills.length;
                        var skill_count = 0;
                        $.each(data.skills, function(index, json) {
                            
                            if (skill_count > 0) {
                                // if multi record available
                                // from second record we need to append input
                                comman_function_append_skills_input(skill_count);
                            }
                            $("#category_list_" + skill_count + " option[value='" + json.category_id + "']").prop("selected", true);

                            //for getting service according to category id
                            //ajax  --------start--------
                            comman_function_for_getting_service(json.category_id, json.service_id, skill_count);
                            //ajax ------end--------

                            $('#service_provider_skill_english_' + skill_count).val(json.key_skill_english);
                            $('#service_provider_skill_amharic_' + skill_count).val(json.key_skill_amharic);

                            skill_count++;
                        });
                        //we are getting skills in array---------start-------
                    }
                },

            });
            //geting customer data for edit -------------END--------
        }
    });
    //Change the mode of service provider modal for add/edit--------------END----------

    //check selected service id unique or deffrent in append inputs----START-----
    $('body').on('change', '.service_list', function() {
        var append_id = $(this).attr('append_id');
        var selected_service = [];
        $.each($(".service_list option:selected"), function() {

            if (jQuery.inArray($(this).val(), selected_service) === -1) {
                //console.log("Not exists in array");
                selected_service.push($(this).val());
            } else {
                //console.log("Exists in array");
                //clear selected value
                $('#service_list_' + append_id).val('');

                swal('Oops...', 'Please select another service it is already selected!', 'warning');
            }
        });
    });
    //check selected service id unique or deffrent in append inputs----END-----

    //Getting service(sub category) ------------START----------------
    $('body').on('change', '.category_list_for_skills, .category_list_for_order', function() {
        var cat_id = $(this).val();
        var append_id = $(this).attr('append_id');

        $('#service_list_' + append_id).empty();
        $('#service_list_' + append_id).html('<option value="">Select Service</option>');

        //ajax  --------start--------
        var service_id = "";
        comman_function_for_getting_service(cat_id, service_id, append_id);
        //ajax ------end--------

    });
    //Getting service(sub category) ------------END-----------------

    //append skills realted input when click on add skilss btn-----START-----
    $('body').on('click', '#add_skill_input', function() {
         var count = $('.append_skill_input_div').length;
         count++;
        //append input------------START-----------------
        comman_function_append_skills_input(count);
        //append input------------END-----------------
    });

    //Once remove button is clicked
    $("body").on("click", ".remove_append_skill_button", function(e) {

        $(this).parents('.append_skill_input_div').remove();
    });
    //append skills realted input when click on add skilss btn-----END------

    // Update Service Provider status ----------------START---------------
    $('body').on('click', '.service_provider_status_change', function() {

        var new_service_provider_table_url = service_provider_table_url.replace("table", "1"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var service_provider_status = $(this).attr('status');
        var user_id = $(this).attr('user_id');
        //ajax-------- start-------------
        $.ajax({
            url: BASE_URL + 'ajax/UpdateServiceProviderStatus/',
            data: {
                service_provider_status: service_provider_status,
                user_id: user_id
            },
            type: 'post',
            success: function(response) {
                if (response == 1) {
                    $("#service_provider_table").load(new_service_provider_table_url);
                } else if (response == 2) { //if session expire
                    window.location.replace(BASE_URL + 'admin/');
                } else {
                    swal('Oops...', 'Something Went wrong!', 'error');
                }
            },
        });
        //ajax-------- end-------------
    });
    // Update Service Provider status ----------------END----------------

    //Change Status as delete of Service Provider --------START--------
    $('body').on('click', '.service_provider_delete', function() {

        var new_service_provider_table_url = service_provider_table_url.replace("table", "2"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var user_id = $(this).attr('user_id');
        swal({
                title: "Are you sure to delete this Service Provider permanently?",
                text: "Once deleted, you will not be able to recover it!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    //ajax-------- start-------------
                    $.ajax({
                        url: BASE_URL + 'ajax/DeleteServiceProviderStatus/',
                        data: {
                            user_id: user_id
                        },
                        type: 'post',
                        success: function(response) {
                            if (response == 1) {
                                swal("Service Provider deleted successfully !", {
                                    icon: "success",
                                });

                                setTimeout(function() {
                                    $("#service_provider_table").load(new_service_provider_table_url);
                                }, 2000);

                            } else if (response == 2) { //if session expire
                                window.location.replace(BASE_URL + 'admin/');
                            } else {
                                swal('Oops...', 'Something Went wrong!', 'error');
                            }
                        },
                    });
                    //ajax-------- end-------------
                } else {
                    //swal("Your imaginary file is safe!");
                }
            });
    });
    //Change Status as delete of Service Provider ------------END----------

    //Search Ordered Service ----------------START---------------------
    $(".search_key").keydown(function(e) {
        if (e.keyCode === 13) {
            $('#search_order').trigger("click");
        }
    });
    $('body').on('click', '#search_order', function() {
        var from_date = $('#fromdate').val();
        var to_date = $('#todate').val();
        var order_status = $('#order_status').val();
        var service_id_for_search = $('#service_id_for_search').val();
        var current_order_page_value = $('#current_order_page_value').val();
        var search = $('.search_key').val();

        if (from_date == '') {
            from_date = "all";
        }
        if (to_date == '') {
            to_date = "all";
        }

        if (order_status == '') {
            order_status = "all";
        }

        if (service_id_for_search == '') {
            service_id_for_search = "all";
        }

        if (current_order_page_value == '') {
            current_order_page_value = "all";
        }

        if (search == '') {
            search = "all";
        } else {

            search = search.trim();
        }

        //for phone number search
        var country_code_inc = search.includes("+251");
        if (country_code_inc == true) {
            search = search.replace("+251", "").trim();
        }

        //for amount search
        var amount_inc = search.includes("Birr");
        if (amount_inc == true) {
            search = search.replace("Birr", "").trim();
        }

        var null_value = search.includes("N/A");
        if (null_value == true) {
            search = search.replace("N/A", "").trim();
        }

        //remove special charcter which is given error by url (means not accepted in url)
        search = remove_special_character_from_entred_search_value(search);
        if (search == '') {
            swal('Oops...', 'Enterd search value is not allowed!', 'warning');
        } else if (from_date != "all" || to_date != "all" || order_status != "all" || service_id_for_search != "all" || search != "all") {
            window.location.replace(BASE_URL + 'admin/orders/0/' + current_order_page_value + '/' + from_date + '/' + to_date + '/' + order_status + '/' + service_id_for_search + '/' + search + '/'); //load only table
        }


    });
    //Search Ordered Service----------------END-----------------------

    //Show service details when select service for order ---------START-------
    $('body').on('change', '#service_list_0', function() {
        var service_id = $(this).val();

        //ajax  --------start--------
        //GET_Service_Data function is used in below points so we need to use it carefully if do any changes -
        //1 - service edit time to show data for edit, 
        //2 - at the time order to get service data according to seleted service id 
        $.ajax({
            url: BASE_URL + 'ajax/GET_Service_Data/', //service detail
            data: {
                service_id: service_id,
            },
            type: 'post',
            success: function(response) {
                if (response != 0) {
                    var data = JSON.parse(response);

                    //(1- fixed, 2- hourly) Default value - 1
                    if (data.service_price_type == 1) {
                        var service_price_type = 'Fixed';
                    } else if (data.service_price_type == 2) {
                        var service_price_type = 'Hourly';
                    } else {
                        var service_price_type = 'Fixed';
                    }
                    $('.service_price_type_for_order').val(service_price_type);
                    $('#price_type_for_order_actual_value').val(data.service_price_type);
                    $('.service_price_for_order').val(data.service_price);
                    $('.visiting_price_for_order').val(data.visiting_price);
                    $('.actual_service_name_for_order').val(data.service_name_english);
                }
            },

        });
        //ajax ------end--------

    });
    //Show service details when select service for order ---------END---------

    //Upload home banner image---------------START----------------------
    $('body').on('click', '#upload_home_banner', function() {

        var banner_image = $('#banner_image').prop('files')[0];
        var form_data = new FormData();
        form_data.append('banner_image', banner_image);

        if (banner_image != undefined) { //if any image selected
            swal({
                title: 'Wait..',
                text: "Please wait and Don't do any action while we are processing your request!",
                type: 'Wait',
                buttons: false,
                closeOnClickOutside: false,
                timer: 2000,
                confirmButtonText: 'Yes, delete it!'
            });
            $.ajax({
                url: BASE_URL + 'ajax/Upload_Save_Banners',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {

                    if (response == 1) {

                        setTimeout(function() {

                            swal("Banner Uploaded  successfully !", {
                                icon: "success",
                            });
                            $('#selected_home_banner_img').attr('src', IMG_PATH + 'example-image.jpg');
                            $('#banner_image').val('');

                            $("#banner_list").load(BASE_URL + 'admin/banners/1');
                        }, 1500);
                    }

                    if (response == "" || response == 0 || response == 2 || response == 4) {
                        swal('Oops...', 'Internal server error!', 'error');
                    }

                    if (response == 3) {
                        swal('Oops...', 'Something Went wrong!', 'error');
                    }

                    if (response == 6) { //if session expire
                        window.location.replace(BASE_URL + 'admin/');
                    }
                }
            });
        }
    });
    //Upload home banner image---------------END----------------------

    //Delete Homer banner image--------------START---------------------
    $('body').on('click', '.delete_banner', function() {

        var banner_id = $(this).attr('banner_id');

        swal({
                title: "Are you sure to delete this Banner permanently?",
                text: "Once deleted, you will not be able to recover it!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    //ajax-------- start-------------
                    $.ajax({
                        url: BASE_URL + 'ajax/DeleteBanner/', //we update status only
                        data: {
                            banner_id: banner_id
                        },
                        type: 'post',
                        success: function(response) {
                            if (response == 1) {
                                swal("Banner deleted successfully !", {
                                    icon: "success",
                                });

                                setTimeout(function() {
                                    $("#banner_list").load(BASE_URL + 'admin/banners/1');
                                }, 2000);

                            } else if (response == 2) { //if session expire
                                window.location.replace(BASE_URL + 'admin/');
                            } else {
                                swal('Oops...', 'Something Went wrong!', 'error');
                            }
                        },
                    });
                    //ajax-------- end-------------
                } else {
                    //swal("Your imaginary file is safe!");
                }
            });
    });
    //Delete Homer banner image--------------START---------------------

    var msg_if_service_is_onging = 'Selected Service is ongoing of this customer';
    //Order: Check service in on going of the customer --------START-----------

    //comman function---#start--
    //#if service is selected and after that mobile number or email id enterd
    //# if mobile number and email id enterd then service id selected
    //#so we have to above condtion check that if any booked service available of the given customer (by mobile number or email id) then  the service is   ongoing  so cant booked service untill it not compleletd.
    function comman_function_check_booked_service_if_on_going(customer_email, customer_mobile, service_id) {
        if (service_id != "") {
            if (customer_mobile == "") {
                swal('', 'Please fill mobile number', 'info');
            } else {
                //ajax-------- start-------------
                $.ajax({
                    url: BASE_URL + 'ajax/Check_BookedService_If_OnGoing_or_completed/',
                    data: {
                        customer_mobile: customer_mobile,
                        customer_email: customer_email,
                        service_id: service_id
                    },
                    type: 'post',
                    success: function(response) {
                        if (response == 1) {
                            $('#submit_status').val('false');
                            swal('Oops..', '' + msg_if_service_is_onging + '', 'info');
                        } else if (response == 2) { //if session expire
                            window.location.replace(BASE_URL + 'admin/');
                        } else if (response == "") {
                            //swal('Oops...', 'Something Went wrong!', 'error');
                        } else {
                            $('#submit_status').val('true');
                        }
                    },
                });
                //ajax-------- end-------------
            }
        } else {
            $('#submit_status').val('true');
        }
    }

    //comman function---#end--

    //Note - Check service id when it is ordering for the customer. It will be unique until order status completed or ignored or canceled) means the user can't order for the same service until the selected service is not finished. But customers can choose other services. (primary id of the service table

    $('body').on('keyup', '.customer_contact_check_for_service', function() {
        var customer_email = $('#customer_email').val();
        var customer_mobile = $(this).val();
        var service_id = $('.check_booked_service').val();

        comman_function_check_booked_service_if_on_going(customer_email, customer_mobile, service_id);
    });

    $('body').on('keyup', '.customer_contact_check_for_service', function() {
        var customer_email = $(this).val();
        var customer_mobile = $('#customer_mobile').val();
        var service_id = $('.check_booked_service').val();

        comman_function_check_booked_service_if_on_going(customer_email, customer_mobile, service_id);
    });

    $('body').on('change', '.check_booked_service', function() {

        var customer_email = $('#customer_email').val();
        var customer_mobile = $('#customer_mobile').val();
        var service_id = $(this).val();
        comman_function_check_booked_service_if_on_going(customer_email, customer_mobile, service_id);
    });

    $("#OrderFormSubmit").submit(function(event) {

        var submit_status = $('#submit_status').val();
        if (submit_status == 'true' && is_mobile_no_valid == true) {
            //ready for submit
            return;
        } else if(submit_status != 'true'){
            swal('Oops..', '' + msg_if_service_is_onging + '', 'info');
        }

        event.preventDefault();
    });
    //Order: Check service in on going of the customer --------END-----------

    //Assign Order --------------------------START------------------------

    //adding order id in to hidden input
    //getting service providers list according to ordered service related
    $('body').on('click', '.assign_order', function() {

        //remove validation error when we are clicking on other order assigning
        $('#service_provider_id').css('border-color', '#fdfdff');
        $('#select_provider_error').text('');

        var service_id = $(this).attr('service_id');


        var selected_order_id = $(this).attr('order_id');
        $('#selected_order_id').val(selected_order_id);

        //show in modal selectbox if already has assign
        var selected_service_provider_id = $(this).attr('service_provider_id');

        if (selected_service_provider_id == 0) {
            $("#service_provider_id").val('');
        } else if (selected_service_provider_id !== 0) {
            $("#service_provider_id option[value=" + selected_service_provider_id + "]").prop("selected", true);
        }

        //Geting service provider data by service id
        if (service_id != "") {
            $('#service_provider_id').empty();
            $('#service_provider_id').html('<option value="">Select service provider</option>');
            //ajax-------- start-------------
            $.ajax({
                url: BASE_URL + 'ajax/GET_Service_Providers_For_Assign/',
                data: {
                    service_id: service_id
                },
                type: 'post',
                success: function(response) {
                    if (response != 0 && response != 2) {
                        var json = JSON.parse(response);
                        $.each(json, function(index, data) {
                            $('#service_provider_id').append("<option value=" + data.user_id + " >" + data.fullname + "</option>");
                        });
                    }
                    if (response == 0) {
                        $('#service_provider_id').append("<option value=''>No service provider available</option>");
                    }

                    if (response == 2) { //if session expire
                        window.location.replace(BASE_URL + 'admin/');
                    }
                },
            });
            //ajax-------- end------------- 
        } else {
            swal('Oops...', 'Something Went wrong!', 'error');
        }
    });

    //Save assign #start--
    $('body').on('click', '#AssignOrder_submit', function() {

        var new_order_table_url = order_table_url.replace("table", "2"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var selected_order_id = $('#selected_order_id').val();
        var service_provider_id = $('#service_provider_id').val();

        if (service_provider_id == "") {
            $('#service_provider_id').css('border-color', 'red');
            $('#select_provider_error').text('Please select serivce provider');
        }

        if (selected_order_id !== "" && service_provider_id !== "") {
            //ajax-------- start-------------
            $.ajax({
                url: BASE_URL + 'ajax/Assign_Order/',
                data: {
                    service_provider_id: service_provider_id,
                    order_id: selected_order_id
                },
                type: 'post',
                success: function(response) {
                    if (response == 1) {

                        $('#assignOrder').modal('hide');
                        $('#assignOrder').find("input,select").val('').end();

                        swal("Order assigned successfully !", {
                            icon: "success",
                        });

                        setTimeout(function() {
                            $("#order_table").load(new_order_table_url);
                        }, 2000);

                    } else if (response == 2) { //if session expire
                        window.location.replace(BASE_URL + 'admin/');
                    } else if (response == 3) {
                        swal('Oops...', 'Already has onging service!', 'warning');
                    } else {
                        swal('Oops...', 'Something Went wrong!', 'error');
                    }
                },
            });
            //ajax-------- end------------- 
        } else {
            //field is missing
        }
    });
    //Save assign #end---

    //Assign Order --------------------------END------------------------

    //Order Status Change ---------------------START-------------------
    $('body').on('change', '.order_status', function() {
        var new_order_table_url = order_table_url.replace("table", "2"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var order_id = $(this).attr('order_id');
        var order_status = $(this).val();
        //0- New/pending, 1- order accept, 2 - on the way, 3 - service started, 4 - service completed, 5 - reject / ignore, 6- canceled
        //order cancel by "Update_Order_Cancel_Status" function with reasone
        //order can cancel till order status "on the way"

        //Check at the when admin want to reject order. If Order no one assign or accepted by any service provider then admin can reject . After accept the service admin cant reject the service.But can do cancel for service provider after accept.
        var service_provider_id = $(this).attr('service_provider_id');

        if (order_id !== "" && order_status !== "") {

            if (service_provider_id != 0 && order_status == 5) {
                swal('Oops...', 'Cannot reject the order because service is in progress !', 'warning');
                $("#order_table").load(new_order_table_url);
            } else if (service_provider_id == 0 && (order_status == 1 || order_status == 2 || order_status == 3 || order_status == 4)) {
                swal('Oops...', 'Please Assign Service Provider!', 'warning');
                $("#order_table").load(new_order_table_url);
            } else {
                //ajax-------- start-------------
                $.ajax({
                    url: BASE_URL + 'ajax/UpdateOrderStatus/',
                    data: {
                        order_status: order_status,
                        order_id: order_id
                    },
                    type: 'post',
                    success: function(response) {

                        if (response == 1) {
                            $("#order_table").load(new_order_table_url);
                        } else if (response == 2) { //if session expire
                            window.location.replace(BASE_URL + 'admin/');
                        } else {
                            swal('Oops...', 'Something Went wrong!', 'error');
                        }
                    },
                });
                //ajax-------- end------------- 
            }
        } else {
            swal('Oops...', 'Internal server error!', 'error');
        }
    });
    //Order Status Change ---------------------END---------------------

    //Order cancel with reasone-----------------START-----------------
    //adding order id in to hidden input
    $('body').on('click', '.cancel_order', function() {

        var selected_order_id = $(this).attr('order_id');
        $('#order_id_for_cancel').val(selected_order_id);

        var service_provider_id = $(this).attr('service_provider_id');
        if (service_provider_id == 0) { //service not accepted by any one or assigned
            $('#no_one_assigned').attr('disabled', '');
        }
        $('#service_provider_id_for_cancel').val(service_provider_id);

        var service_id = $(this).attr('service_id');
        $('#service_id_for_cancel').val(service_id);

        var cancel_status_value = $(this).attr('order_db_cancel_value'); //value should be according to db comment
        $('#cancel_status_value').val(cancel_status_value);

        // do texarea clean when click on cancel button
        $('#cancel_reason').val('');
    });

    //cancel order submit #start-------
    $('body').on('click', '#cancel_order_status_save', function() {
        var new_order_table_url = order_table_url.replace("table", "2"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var cancel_for_whom = $('#cancel_for_whom').val(); //4 = customer, 3 = service provider 

        if (cancel_for_whom == "") {
            $('#cancel_for_whom').css('border-color', 'red');
            $('#select_role_error').text('Please select role');
        }

        var order_id = $('#order_id_for_cancel').val();
        var service_provider_id = $('#service_provider_id_for_cancel').val();
        var service_id = $('#service_id_for_cancel').val();

        //order cancel by "Update_Order_Cancel_Status" function with reasone
        //order can cancel till order status "on the way"

        var cancel_reason = $('#cancel_reason').val().trim();

        if (cancel_reason == "") {
            $('#cancel_reason').css('border-color', 'red');
            $('#cancel_reason_error').text('Please enter cancel reason');
        }

        if (cancel_for_whom == 3) {
            var check_condtion = '&& service_id !== "" && service_provider_id !== "" && service_provider_id !== 0';
        } else {
            var check_condtion = '';
        }

        if (order_id !== "" && cancel_for_whom !== "" && cancel_reason !== "" + check_condtion) {
            //ajax-------- start-------------
            $.ajax({
                url: BASE_URL + 'ajax/Update_Order_Cancel_Status/',
                data: {
                    order_id: order_id,
                    cancel_reason: cancel_reason,
                    cancel_for_whom: cancel_for_whom,
                    service_provider_id: service_provider_id,
                    service_id: service_id
                },
                type: 'post',
                success: function(response) {
                    if (response == 1) {
                        $('#cancelordermodal').modal('hide');
                        $('#cancelordermodal').find("textarea").val('').end();

                        swal("Order canceled successfully !", {
                            icon: "success",
                        });

                        setTimeout(function() {
                            $("#order_table").load(new_order_table_url);
                        }, 2000);
                    } else if (response == 2) { //if session expire
                        window.location.replace(BASE_URL + 'admin/');
                    } else if (response == 3) {
                        swal('Oops...', 'Already canceled for this Service Provider!', 'warning');
                    } else if (response == 4) {
                        swal('Oops...', 'Service Provider detail missing!', 'error');
                    } else {
                        swal('Oops...', 'Something Went wrong!', 'error');
                    }
                },
            });
            //ajax-------- end------------- 
        } else {
            //field is missing
        }
    });
    //cancel order submit #end-------

    //Order cancel with reasone-----------------END------------------

    //Order - Auto fill customer details if they exist-------START--------------
    //When order create enter the mobile number if customer is exist then customer details will be auto fill
    //getting mobile number for showing in the list if match 
    $("#SelectMobileInput").keyup(function(e) { 
        var mobile_number = $(this).val();

        $('#SelectMobileDropdown').empty();
        $('#customer_fullname').val('');
        $('#customer_email').val('');
        $('#age').val('');
        $("#customer_location").val('');
        $('#customer_latitude').val('');
        $('#customer_longitude').val('');
        $("#gender").val('');
        $('#SelectMobileDropdown').addClass("d-none");

        //customer details fill if enter mobile number is exact match otherwise mobile number will be show in dropdown list
        if (mobile_number != "") {
            $.ajax({
                url: BASE_URL + 'ajax/Get_Customers_Mobile_Number/',
                data: {
                    mobile_number: mobile_number
                },
                type: 'post',
                success: function(response) {
                    if (response != 0 && response != 2) {

                        var json = JSON.parse(response);

                        $.each(json, function(index, data) {

                            $('#SelectMobileDropdown').removeClass("d-none");

                            //mobile search list
                            $('#SelectMobileDropdown').append("<div><a href='javascript:void(0)' user_id='" + data.user_id + "' class='get_customer_detail_for_auto_fill'>" + data.mobile + "</a></div>");

                            if (json.length == 1) {
                                $('#customer_fullname').val(data.fullname);
                                $('#customer_email').val(data.email);
                                $('#age').val(data.age);
                                $('#customer_location').val(data.google_map_pin_address);
                                $('#customer_latitude').val(data.latitude);
                                $('#customer_longitude').val(data.longitude);

                                $("#gender option[value='" + data.gender + "']").prop("selected", true);

                                if (data.profile_image != "") {
                                    $('#selected_img').attr('src', BASE_URL + data.profile_image);
                                } else {
                                    $('#selected_img').attr('src', IMG_PATH + 'avatar/avatar-1.png');
                                }
                            } else {

                                $('#customer_fullname').val('');
                                $('#customer_email').val('');
                                $('#SelectMobileDropdown').val('');
                                $('#age').val('');
                                $("#gender").val('');
                                $("#customer_location").val('');
                                $('#customer_latitude').val('');
                                $('#customer_longitude').val('');
                            }

                        });
                    }
                    if (response == 0) {
                        $('#customer_fullname').val('');
                        $('#customer_email').val('');
                        $('#SelectMobileDropdown').val('');
                        $('#age').val('');
                        $("#gender").val('');
                        $("#customer_location").val('');
                        $('#customer_latitude').val('');
                        $('#customer_longitude').val('');
                    }

                    if (response == 2) { //if session expire
                        window.location.replace(BASE_URL + 'admin/');
                    }
                },
            });
        }
    });

    //customer details fill if click from dropdown list
    $('body').on('click', '.get_customer_detail_for_auto_fill', function() {
        var user_id = $(this).attr('user_id');
        $.ajax({
            url: BASE_URL + 'ajax/GET_User_Data/', //customer detail
            data: {
                user_id: user_id,
            },
            type: 'post',
            success: function(response) {
                if (response != 0) {
                    var data = JSON.parse(response);
                    $('#customer_fullname').val(data.fullname);
                    $('#customer_email').val(data.email);
                    $('#SelectMobileInput').val(data.mobile);
                    $('#age').val(data.age);
                    $('#customer_location').val(data.google_map_pin_address);
                    $('#customer_latitude').val(data.latitude);
                    $('#customer_longitude').val(data.longitude);

                    $("#gender option[value='" + data.gender + "']").prop("selected", true);

                    if (data.profile_image != "") {
                        $('#selected_img').attr('src', BASE_URL + data.profile_image);
                    } else {
                        $('#selected_img').attr('src', IMG_PATH + 'avatar/avatar-1.png');
                    }
                }
            },
        });
    });

    //remove drop down
    $('body').on('click', '.modal', function() {
        $('#SelectMobileDropdown').addClass("d-none");
    });
    //Order - Auto fill customer details if they exist-------END--------------

    // Set Timer ---------------------START---------------
    //for showing on order list table when service will started 
    setInterval(function() {
        $.each($(".clock_timer"), function() {
            var service_started_time = $(this).attr('started_time');
            var service_type = $(this).attr('service_type');
            var this_id = $(this).attr('id');

            if (service_started_time > 0 && service_type == 2) {
                $.ajax({
                    url: BASE_URL + 'ajax/GET_Current_Duration_From_Start_Time/',
                    data: {
                        service_started_time: service_started_time,
                    },
                    type: 'post',
                    success: function(response) {
                        if (response != 0) {
                            console.log(response);
                            $('#' + this_id).text(response);
                        }
                        if (response == 2) { //if session expire
                            window.location.replace(BASE_URL + 'admin/');
                        }
                    },
                });
            }

        });
    }, 1000);
    // Set Timer ---------------------END-----------------

    //Show skills when select available service -----------START--------------
    // For service provider  details page 
    $('body').on('click', '#show_skills', function() {
        var skill_id = $(this).val();
        $.ajax({
            url: BASE_URL + 'ajax/GET_Skills/',
            data: {
                skill_id: skill_id,
            },
            type: 'post',
            success: function(response) {
                if (response != 0) {
                    console.log(response);
                    $('#get_skills').text(response);
                }
                if (response == 2) { //if session expire
                    window.location.replace(BASE_URL + 'admin/');
                }
            },
        });
    });
    //Show skills when select available service -----------END---------------

    //Check google address select or not from autocomplete dropdown--------START------
    $(".select_google_location").keyup(function(event) {
        var location = $(this).val();
        if (location == "") {
            $('.hide_this_error').addClass('d-none');

            $(this).css('border-color', 'red');
            $('#cus_location_error').text('Please select location');

            $('#customer_latitude').val("");
            $('#customer_longitude').val("");
        } else {
            $('.hide_this_error').removeClass('d-none');
            $(this).css('border-color', '#28a745');
            $('#cus_location_error').text('');
        }
    });
    //Check google address select or not from autocomplete dropdown--------END--------

    //if latitude enterd then order form will be submit----START---
    $("#OrderFormSubmit").submit(function(event) {

        var customer_latitude = $('#customer_latitude').val();
        var customer_longitude = $('#customer_longitude').val();

        if (customer_latitude != "" && customer_longitude != "") {
            //ready for submit
            $('.select_google_location').css('border-color', '#28a745');
            $('#cus_location_error').text('');
            $('.hide_this_error').removeClass('d-none');
            return;
        } else {
            // alert('invalid');
            $('.hide_this_error').addClass('d-none');
            $('.select_google_location').css('border-color', 'red');
            $('#cus_location_error').text('Please select location');
        }

        event.preventDefault();
    });
    //if latitude enterd then order form will be submit-----END------

    //Notification mark as read -------------START-------------
    $('body').on('click', '#notification_mark_as_read', function() {

        var read_status = 1; //update value with 1
        //ajax-------- start-------------
        $.ajax({
            url: BASE_URL + 'ajax/UpdateNotificationReadStatus/',
            data: {
                read_status: read_status
            },
            type: 'post',
            success: function(response) {
                if (response == 1) {
                    $("#notification_list").load(BASE_URL + 'admin/navbar_notification_list');
                    $(".notification-toggle").removeClass('beep');
                    $("#notification_mark_as_read").addClass('d-none');
                    if (notification_table_url != undefined) {
                        var new_notification_table_url = notification_table_url.replace("table", "1"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case
                        $("#notification_table_list").load(new_notification_table_url);
                    }
                } else if (response == 2) { //if session expire
                    window.location.replace(BASE_URL + 'admin/');
                } else {
                    swal('Oops...', 'Something Went wrong!', 'error');
                }
            },
        });
        //ajax-------- end-------------
    });
    //Notification mark as read -------------END-------------

    //Change the mode of admin user modal for add/edit--------------START--------
    $('body').on('click', '.add_edit_admin_user', function() {
        var mode = $(this).attr('mode'); //1 = add , 2 - edit

        $('#add_edit_AdminUserModal').find("input,input[type=file],select").val('').end();
        $('#check_mobile_if_exist').val('false');
        $('#check_email_if_exist').val('false');
        $('.check_email_if_exist').css('border-color', '#28a745');
        $('#email_error').text('');
        if (mode == 1) { //add
            $('.admin_user_modal_title').text('Add User');
            $('#user_form_mode').val(1);

        } else if (mode == 2) {
            $('.admin_user_modal_title').text('Edit User');
            $('#user_form_mode').val(2);

            //geting user data for edit -------------START-------
            var user_id = $(this).attr('user_id');
            $('#edit_user_id').val(user_id);
            $.ajax({
                url: BASE_URL + 'ajax/GET_User_Data/', //user detail
                data: {
                    user_id: user_id,
                },
                type: 'post',
                success: function(response) {
                    if (response != 0) {
                        var data = JSON.parse(response);
                        $('#admin_user_fullname').val(data.fullname);
                        $('#admin_user_email').val(data.email);
                        $('#admin_user_mobile').val(data.mobile);
                    }
                },

            });
            //geting user data for edit -------------END--------
        }
    });
    //Change the mode of admin user modal for add/edit--------------END----------

    // Update Admin User status ----------------START---------------
    $('body').on('click', '.user_status_change', function() {

        var new_admin_table_url = admin_table_url.replace("table", "1"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var admin_user_status = $(this).attr('status');
        var user_id = $(this).attr('user_id');
        //ajax-------- start-------------
        $.ajax({
            url: BASE_URL + 'ajax/UpdateUserStatus/', //as for now same funcation we are using  for customer and admin user status change
            data: {
                user_status: admin_user_status,
                user_id: user_id,
                user_management: 1
            },
            type: 'post',
            success: function(response) {
                if (response == 1) {
                    $("#user_list").load(new_admin_table_url);
                } else if (response == 2) { //if session expire
                    window.location.replace(BASE_URL + 'admin/');
                } else {
                    swal('Oops...', 'Something Went wrong!', 'error');
                }
            },
        });
        //ajax-------- end-------------
    });
    // Update Admin User status ----------------END----------------

    //Change Status as delete of admin user --------START--------
    $('body').on('click', '.admin_user_delete', function() {

        var new_admin_table_url = admin_table_url.replace("table", "2"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var user_id = $(this).attr('user_id');
        swal({
                title: "Are you sure to delete this user permanently?",
                text: "Once deleted, you will not be able to recover it!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    //ajax-------- start-------------
                    $.ajax({
                        url: BASE_URL + 'ajax/DeleteUserStatus/',
                        data: {
                            user_id: user_id,
                            user_management_page: 1
                        },
                        type: 'post',
                        success: function(response) {
                            if (response == 1) {
                                swal("User deleted successfully !", {
                                    icon: "success",
                                });

                                setTimeout(function() {
                                    $("#user_list").load(new_admin_table_url);
                                }, 2000);

                            } else if (response == 2) { //if session expire
                                window.location.replace(BASE_URL + 'admin/');
                            } else {
                                swal('Oops...', 'Something Went wrong!', 'error');
                            }
                        },
                    });
                    //ajax-------- end-------------
                } else {
                    //swal("Your imaginary file is safe!");
                }
            });
    });
    //Change Status as delete of admin user ------------END----------

    //Search Admin User----------------START---------------------
    $(".search_key").keydown(function(e) {
        if (e.keyCode === 13) {
            $('#search_admin_user').trigger("click");
        }
    });
    $('body').on('click', '#search_admin_user', function() {

        var from_date = $('#fromdate').val();
        var to_date = $('#todate').val();
        var user_status = $('#user_status').val(); //(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
        var search = $('.search_key').val();

        if (from_date == '') {
            from_date = "all";
        }
        if (to_date == '') {
            to_date = "all";
        }

        if (user_status == '') {
            user_status = "all";
        }

        if (search == '') {
            search = "all";
        } else {

            search = search.trim();
        }

        //for phone number search
        var country_code_inc = search.includes("+251");
        if (country_code_inc == true) {
            search = search.replace("+251", "").trim();
        }

        var null_value = search.includes("N/A");
        if (null_value == true) {
            search = search.replace("N/A", "").trim();
        }

        //remove special charcter which is given error by url (means not accepted in url)
        search = remove_special_character_from_entred_search_value(search);
        if (search == '') {
            swal('Oops...', 'Enterd search value is not allowed!', 'warning');
        } else if (from_date != "all" || to_date != "all" || user_status != "all" || search != "all") {
            window.location.replace(BASE_URL + 'admin/user_management/0/' + from_date + '/' + to_date + '/' + user_status + '/' + search + '/'); //load only table
        }

    });
    //Search Admin User----------------END-----------------------

    //Dashboard Filter----------------START---------------------
    $(".search_key").keydown(function(e) {
        if (e.keyCode === 13) {
            $('#filter_dashboard').trigger("click");
        }
    });
    $('body').on('click', '#filter_dashboard', function() {

        var from_date = $('#fromdate').val();
        var to_date = $('#todate').val();

        if (from_date == '') {
            from_date = "all";
        }
        if (to_date == '') {
            to_date = "all";
        }

        if (from_date != "all" || to_date != "all") {
            window.location.replace(BASE_URL + 'admin/dashboard/' + from_date + '/' + to_date + '/'); //load only table
        }
    });
    //Dashboard Filter----------------END-----------------------

    //create zone by geo fence ----------------START-----------
    $(document).ready(function() {
        if (typeof InitMap === "function") {
            InitMap();
        }
        if (typeof Getpolygoncoordinates === "function") {
            Getpolygoncoordinates();
        }
    });
    //create zone by geo fence ----------------END-------------

    // Update Admin zone status ----------------START---------------
    $('body').on('click', '.zone_status_change', function() {

        var new_zone_table_url = zone_table_url.replace("table", "1"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var zone_status = $(this).attr('status');
        var zone_id = $(this).attr('zone_id');
        //ajax-------- start-------------
        $.ajax({
            url: BASE_URL + 'ajax/UpdateZoneStatus/', //as for now same funcation we are using  for customer and admin zone status change
            data: {
                zone_status: zone_status,
                zone_id: zone_id
            },
            type: 'post',
            success: function(response) {
                if (response == 1) {
                    $("#zone_list").load(new_zone_table_url);
                } else if (response == 2) { //if session expire
                    window.location.replace(BASE_URL + 'admin/');
                } else {
                    swal('Oops...', 'Something Went wrong!', 'error');
                }
            },
        });
        //ajax-------- end-------------
    });
    // Update Admin zone status ----------------END----------------

    //Change Status as delete of admin zone --------START--------
    $('body').on('click', '.zone_delete', function() {

        var new_zone_table_url = zone_table_url.replace("table", "2"); // if action mode enable disable then it will repalce table to  1 same as  2 in delete case

        var zone_id = $(this).attr('zone_id');
        swal({
                title: "Are you sure to delete this zone permanently?",
                text: "Once deleted, you will not be able to recover it!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    //ajax-------- start-------------
                    $.ajax({
                        url: BASE_URL + 'ajax/DeleteZoneStatus/',
                        data: {
                            zone_id: zone_id,
                            zone_management_page: 1
                        },
                        type: 'post',
                        success: function(response) {
                            if (response == 1) {
                                swal("zone deleted successfully !", {
                                    icon: "success",
                                });

                                setTimeout(function() {
                                    $("#zone_list").load(new_zone_table_url);
                                }, 2000);

                            } else if (response == 2) { //if session expire
                                window.location.replace(BASE_URL + 'admin/');
                            } else {
                                swal('Oops...', 'Something Went wrong!', 'error');
                            }
                        },
                    });
                    //ajax-------- end-------------
                } else {
                    //swal("Your imaginary file is safe!");
                }
            });
    });
    //Change Status as delete of admin zone ------------END----------

    //search filter zone ------------START--------------
    $(".search_key").keydown(function(e) {
        if (e.keyCode === 13) {
            $('#search_exist_zone').trigger("click");
        }
    });
    $('body').on('click', '#search_exist_zone', function() {

        var from_date = $('#fromdate').val();
        var to_date = $('#todate').val();
        var zone_status = $('#zone_status').val(); //(1- Enable, 2- Disable/Block, 3- Delete ) Default - 1
        var search = $('.search_key').val();

        if (from_date == '') {
            from_date = "all";
        }
        if (to_date == '') {
            to_date = "all";
        }

        if (zone_status == '') {
            zone_status = "all";
        }

        if (search == '') {
            search = "all";
        } else {

            search = search.trim();
        }

        //for phone number search
        var country_code_inc = search.includes("+251");
        if (country_code_inc == true) {
            search = search.replace("+251", "").trim();
        }

        var null_value = search.includes("N/A");
        if (null_value == true) {
            search = search.replace("N/A", "").trim();
        }

        //remove special charcter which is given error by url (means not accepted in url)
        search = remove_special_character_from_entred_search_value(search);
        if (search == '') {
            swal('Oops...', 'Enterd search value is not allowed!', 'warning');
        } else if (from_date != "all" || to_date != "all" || zone_status != "all" || search != "all") {
            window.location.replace(BASE_URL + 'admin/zone_management/0/' + from_date + '/' + to_date + '/' + zone_status + '/' + search + '/'); //load only table
        }

    });
    //search filter zone ------------END----------------

    //Check mobile number exist ----------START----------
    $(".check_contact_number_exist").keyup(function(e) {
        var mobile = $(this).val();
         if(typeof if_page_admin_profle !== 'undefined'){
            var is_page_admin_profle = true;
        }else{
            var is_page_admin_profle = false;
        }
        if (mobile == '0000000000') {
            $('#check_mobile_if_exist').val('true');
            $('.check_contact_number_exist').css('border-color', 'red');
            $('#mobile_no_error').text('Please enter valid number');
        } else {
            $.ajax({
                url: BASE_URL + 'ajax/CheckMobileNoExist/', //user detail
                data: {
                    mobile: mobile,is_page_admin_profle:is_page_admin_profle
                },
                type: 'post',
                success: function(response) {
                    if (response == 1) {
                        $('.check_contact_number_exist').css('border-color', 'red');
                        $('#mobile_no_error').text('Mobile number already exist');
                        $('#check_mobile_if_exist').val('true');
                    } else if (response == 2) { //if session expire
                        window.location.replace(BASE_URL + 'admin/');
                    } else {
                        $('.check_contact_number_exist').css('border-color', '#ccc');
                        $('#mobile_no_error').text('');
                        $('#check_mobile_if_exist').val('false');
                    }
                },

            });
        }
    });
    //Check mobile number exist ----------END----------

    //Check email exist ----------START----------
    $(".check_email_if_exist").keyup(function(e) {
        $('.check_email_if_exist').css('border-color', '#ccc');
        $('#email_error').text('');
        $('#check_email_if_exist').val('false');

       // from getting all current open open form
       
       if($('#customer_form_mode').val() == 2){
            var user_id =  $('#edit_customer_id').val(); 
       }else if($('#service_provider_form_mode').val() == 2){
            var user_id =  $('#edit_service_provider_id').val(); 
       }else if($('#user_form_mode').val() == 2){
            var user_id =  $('#edit_service_provider_id').val(); 
       }else{
            var user_id = "";
       }
        
        var email = $(this).val();
        if(typeof if_page_admin_profle !== 'undefined'){
            var is_page_admin_profle = true;
            var user_id = "";
        }else{
            var is_page_admin_profle = false;
        }
        
        if (email != "") {
            $.ajax({
                url: BASE_URL + 'ajax/CheckEmailExist/', //user detail
                data: {
                    email: email,is_page_admin_profle:is_page_admin_profle,user_id:user_id
                },
                type: 'post',
                success: function(response) {
                    if (response == 1) {
                        $('.check_email_if_exist').css('border-color', 'red');
                        $('#email_error').text('Email id already exist');
                        $('#check_email_if_exist').val('true');
                    } else if (response == 2) { //if session expire
                        window.location.replace(BASE_URL + 'admin/');
                    } else {
                        $('.check_email_if_exist').css('border-color', '#ccc');
                        $('#email_error').text('');
                        $('#check_email_if_exist').val('false');
                    }
                },

            });
        } else {
            $('#check_email_if_exist').val('false');
        }

    });
    //Check email exist ----------END----------

    //Service Provider form submit -------START----------
    $("#ServiceProviderFormSubmit").submit(function(event) {
        var check_mobile_if_exist = $('#check_mobile_if_exist').val();
        var check_email_if_exist = $('#check_email_if_exist').val();
        var service_provider_email = $('#service_provider_email').val();

        var provider_latitude = $('#provider_latitude').val();
        var provider_longitude = $('#provider_longitude').val();
        
        if (service_provider_email == "") {
            //alert();
            var check_email_if_exist = 'false';
        }

        if (provider_latitude != "" && provider_longitude != "") {
            $('.select_google_location').css('border-color', '#28a745');
            $('#provider_location_error').text('');
            $('.hide_this_error').removeClass('d-none');
            
        } else {
            $('.hide_this_error').addClass('d-none');
            $('.select_google_location').css('border-color', 'red');
            $('#provider_location_error').text('Please select location');
        }

       // alert(is_user_age_valid+check_mobile_if_exist+check_email_if_exist);
        if (is_user_age_valid == true && check_mobile_if_exist == 'false' && check_email_if_exist == 'false' && provider_latitude != "" && provider_longitude != "") {
            //ready for submit
            return;
        } else {
            //alert('invalid');
        }
        event.preventDefault();
    });
    //Service Provider form submit -------END------------

    //User (Customer service) form submit -------START----------
    $("#UserFormSubmit").submit(function(event) {
        var check_mobile_if_exist = $('#check_mobile_if_exist').val();
        var check_email_if_exist = $('#check_email_if_exist').val();
        if (check_mobile_if_exist == 'false' && check_email_if_exist == 'false') {
            //ready for submit
            return;
        } else {
            //alert('invalid');
        }
        event.preventDefault();
    });
    //User (Customer service) form submit -------END------------

    //Customer form submit -------START----------
    $("#CustomerFormSubmit").submit(function(event) {
        var check_mobile_if_exist = $('#check_mobile_if_exist').val();
        var check_email_if_exist = $('#check_email_if_exist').val();
        var customer_email = $('#customer_email').val();
        
        if (customer_email == "") {
            //alert();
            var check_email_if_exist = 'false';
        }
        if (is_user_age_valid == true && check_mobile_if_exist == 'false' && check_email_if_exist == 'false') {
            //ready for submit
            return;
        } else {
            //alert('invalid');
        }
        event.preventDefault();
    });
    //Customer form submit -------END------------

    //Setting form submit ---------START----------
    $("#SettingFormSubmit").submit(function(event) {
        if (is_mobile_no_valid == true) {
            //ready for submit
            return;
        } else {
            //alert('invalid');
        }
        event.preventDefault();
    });
    //Setting form submit ---------END------------

    //remove error msg from input---------START--------
    $('body').on('click', '.add_order_btn', function() {
        $('.check_mobile_number_valid').css('border-color', '#ccc');
        $('#mobile_no_error').text(''); 
    });
    //remove error msg from input---------END--------

    //Admin form submit -------START----------
    $("#AdminProfileFormSubmit").submit(function(event) {
        var check_mobile_if_exist = $('#check_mobile_if_exist').val();
        var check_email_if_exist = $('#check_email_if_exist').val();
        if (check_mobile_if_exist == 'false' && check_email_if_exist == 'false') {
            //ready for submit
            return;
        } else {
            //alert('invalid');
        }
        event.preventDefault();
    });
    //Admin form submit -------END------------

    //Check city name is exit if on enter ---START----
    $('body').on('keyup', '#check_if_exist_city_name', function() {
        var city_name  = $(this).val();
        $('.form-control').removeClass('remove_error_input_border');
        if(city_name != ""){
            $.ajax({
                url: BASE_URL + 'ajax/CheckCityExist/', //city detail
                data: {
                    city_name:city_name
                },
                type: 'post',
                success: function(response) {
                    if (response == 1) {
                        $('.city_name_input').css('border-color', 'red');
                        $('#city_name_error').text('Name already exist');
                        $('#check_city_name_if_exist').val('true');
                    } else if (response == 2) { //if session expire
                        window.location.replace(BASE_URL + 'admin/');
                    } else {
                        $('.city_name_input').css('border-color', '#ccc');
                        $('#city_name_error').text('');
                        $('#check_city_name_if_exist').val('false');
                    }
                },
    
            });
        }else{
            $('.city_name_input').removeAttr('style');
            $('.form-control').addClass('remove_error_input_border');
            $('#city_name_error').text('');
        }
       
    });
    //Check city name is exit if on enter ---END----

    //Add City form submit -------START----------
    $("#AddCityForm").submit(function(event) {
        var check_city_name_if_exist = $('#check_city_name_if_exist').val();
        if (check_city_name_if_exist == 'false') {
            //ready for submit
            return;
        } else {
            //alert('invalid');
        }
        event.preventDefault();
    });
    //Add City form submit -------END------------

}); // End of  $(document).ready(function() {

/*Select dropdown with search filter used in order page when order create  ----------start*/
function SearchDropdownFunction(select_dropdown_id) {
    // $('#'+select_dropdown_id).toggleClass("d-none");//if want to toggle use this
}

function filterFunction(select_dropdown_id, SelectInput) {
    var input, filter, ul, li, a, i, div, txtValue;
    input = document.getElementById(SelectInput);
    filter = input.value.toUpperCase();
    div = document.getElementById(select_dropdown_id);
    a = div.getElementsByTagName("div"); //change element which you want search 
    for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}
/*Select dropdown with search filter used in order page when order create   ----------end*/