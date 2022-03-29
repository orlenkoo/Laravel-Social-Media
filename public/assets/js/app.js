$(document).foundation();

$("input, select, textarea, form").attr("autocomplete", "off");

$('.datepicker').datetimepicker({
    timepicker: false,
    format: 'Y-m-d',
    lang: 'en',
    scrollInput : false
});


$('.timepicker').datetimepicker({
    datepicker: false,
    format: 'g:i A',
    formatTime:'g:i A',
    lang: 'en',
    step : 30,
    scrollInput : false
});

$('.datetimepicker').datetimepicker({
    datepicker: true,
    format: 'Y-m-d H:i',
    step : 30,
    scrollInput : false
});

// Tippy Codes - Tool Tips
tippy('.web360-tooltip', {
    delay: 100,
    arrow: true,
    arrowType: 'round',
    size: 'large',
    duration: 500,
    animation: 'scale'
});

function compareTime(time1, time2) {
    return new Date(time1) > new Date(time2); // true if time1 is later
}

function showHidePanel(panel_id) {
    $('#'+panel_id).toggle();
}

$(".numbersonly").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter, . , and -
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 189, 109]) !== -1 ||
        // Allow: Ctrl+A, Command+A
        (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
        // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});

$.validate({
    onSuccess: function () {
        $('.save_bar').css('display', 'none');
        $('.loading_animation').css('display', 'block');
    },
});

//session expiration and error notifications
$.ajaxSetup({
    statusCode: {
        401: function() {
            notifyLogoutErrorMessage();
        },
        500: function() {
            $.notify('An error has occurred, please try again later.');
        }
    }
});

var notifyLogoutErrorMessage = (function() {
    var executed = false;
    return function() {
        if (!executed) {
            executed = true;
            $.notify('Your Session has been expired, you will be redirected to login page.');
            setTimeout(function(){
                window.location.href = '/login';
            },3000);
        }
    };
})();

function copyToClipBoard(div_id_to_copy) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($('#'+div_id_to_copy).text()).select();
    document.execCommand("copy");
    $.notify('Copied to clipboard: '+$temp.val(), "success");
    $temp.remove();
}

// to remove duplicate entries from an array
function removeDuplicatesFromArray(arr){
    console.log('removeDuplicatesFromArray -- arr -- ' + arr);
    var unique_array = []
    for(var i = 0;i < arr.length; i++){
        if(unique_array.indexOf(arr[i]) == -1){
            unique_array.push(arr[i])
        }
    }
    return unique_array
}

/*
* ajax functions for update and create
 */
function ajaxUpdateIndividualFieldsOfModel(table_name, row_id, column_name, column_value, field_id, model_name, required_field) {

    console.log('table_name -- ' + table_name);
    console.log('row_id -- ' + row_id);
    console.log('column_name -- ' + column_name);
    console.log('column_value -- ' + column_value);
    console.log('field_id -- ' + field_id);
    console.log('model_name -- ' + model_name);
    console.log('required_field -- ' + required_field);

    if(required_field) {
        if(!column_value) {
            $.notify('This is a Required Field. Cannot be empty', "error");
            $('#'+field_id).focus();
            return false;
        }
    }

    $('#'+field_id).prop('disabled', true);

    $.post("/common-utility/ajax/update-individual-fields-of-model",
        {
            table_name: table_name,
            row_id: row_id,
            column_name: column_name,
            column_value: column_value,
            model_name: model_name,
        },
        function (data, status) {

            $.notify(data, "success");

            $('#'+field_id).prop('disabled', false);
        });
}

/**
 * Setup the Ajax Forms
 * @param {string} form_id                - The id value of the form tag
 * @param {string} action                 - The action performed by the function eg : create , update .
 * @param {string} pop_up_close           - The id value of the popup modal.
 * @param {function} callback             - The post ajax call back function.
 * @param {function} callback_before_post - The pre ajax call back function.
 * @param {object} custom_validation      - The form jquery validation settings.
 *
 *
 * Sample Usage

                 setUpAjaxForm('preferences_form_id', 'create', 'preferences_form_popup',
                 function(){
                                loadOrganizationPreferenceLogoImage();
                            },
                 function(){
                                var logo_image_value = $("#logo_image").val();
                                var logo_image = $("#logo_image")[0];

                                if(logo_image_value != '' && logo_image.files[0].size*0.001 > 200){
                                    return false;
                                }
                            }
                 );

 */
function setUpAjaxForm(form_id,action,pop_up_close,callback, callback_before_post, validation) {

    validation = validation || {};
    validation.form = "#"+form_id;//adding form id for validation

    // sometimes people forget to pass the #
    if(pop_up_close) {
        if(pop_up_close.indexOf("name=") < 0) {
            if(pop_up_close.indexOf("#") < 0) {
                pop_up_close = "#"+pop_up_close;
            }
        }
    }


    $.validate(validation);

    var errors = [],
        // Validation configuration
        conf = {
            onElementValidate : function(valid, $el, $form, errorMess) {
                if( !valid ) {
                    // gather up the failed validations
                    errors.push({el: $el, error: errorMess});
                }
            }
        },
        // Optional language object
        lang = {
        };

    $("#"+form_id).submit(function (e) { //binding the submit function of the form
        errors = [];
        if( !$(this).isValid(lang, conf, false) ) {
            return;
        } else {
            // The form is valid
        }

        e.preventDefault();

        $('.save_bar').css('display', 'none');
        $('.loading_animation').css('display', 'block');

        var form = this;
        var form_data = new FormData(this);
        console.log('form_data -- '+form_data);
        var form_url = $(form).attr("action");
        var form_method = $(form).attr("method").toUpperCase();

        var value = $(document.activeElement).val();
        form_data.append('submit_button_value', value);

        if(callback_before_post){
            callback_before_post();
        }

        $.ajax({
            url: form_url,
            type: form_method,
            data: form_data,
            cache:false,
            contentType: false, //file upload
            processData: false, // file upload

            success:function(response, status, xhr){

                var response_type = xhr.getResponseHeader("content-type") || "";

                console.log("response_type -- " + response_type);

                $('.save_bar').css('display', 'block');
                $('.loading_animation').css('display', 'none');

                if (response_type.indexOf('json') > -1) {
                    console.log("json response");
                    var status = response.status;
                    var message = response.message;

                    if(status == 'fail'){
                        $.notify(message);
                    }else{
                        $.notify(message,"success");

                        if(pop_up_close){
                            $(pop_up_close).foundation('close');
                        }
                        if(callback){
                            callback(response);
                        }
                    }

                }else{
                    console.log("non json response");
                    $.notify(response, "success");

                    if(pop_up_close){
                        $(pop_up_close).foundation('close');
                    }
                    if(callback){
                        callback(response);
                    }
                }

            },
            error: function (jqXHR, exception) {
                $.notify("Error Occurred when trying to save the form");
                $('.save_bar').css('display', 'block');
                $('.loading_animation').css('display', 'none');
            }
        });

    });
}

function closePopup(popup_id) {
    $('#'+popup_id).foundation('close');
}
function openPopup(popup_id) {
    $('#'+popup_id).foundation('open');
}

function resetForm(form_id) {
    document.getElementById(""+form_id).reset();
}

function setupCKEditor(element,data,toolbar_type){

    try {

        if(toolbar_type == 'Basic'){
            CKEDITOR.replace(element,{
                toolbar : 'Basic',
                allowedContent: true
            });

        }else{
            CKEDITOR.replace(element,{
                allowedContent: true
            });

        }



        //Set Initial Editor Content
        CKEDITOR.instances[element].setData(data);

        //Initial update root content of the element
        CKEDITOR.instances[element].updateElement();

        //Update root content of the element on change of editor content
        CKEDITOR.instances[element].on('change', function() { CKEDITOR.instances[element].updateElement() });

       } catch (err) {

            console.log('CK EDITOR Error: '+err.message);
       }
}

// hopscotch tour
// Define the tour!
// var tour_first_time = {
//     id: "web360-hopscotch-tour",
//     onEnd: function() {
//         $.ajax({
//             url: '/employees/ajax-update-guided-tour-status'
//         }).done(function (data) {
//             $.notify('Updated Successfully', 'success');
//         });
//     },
//     steps: [
//         {
//             title: "Welcome to the new WEB360 dashboard.",
//             content: "Please click next to see whats new.",
//             target: "wh_tour_step1",
//             placement: "bottom"
//         },
//         {
//             title: "The Old Dashboard Links",
//             content: "Don't worry all your leads are still here. Click here to view the old menu.",
//             target: 'wh_tour_step2',
//             placement: "bottom"
//         },
//         {
//             title: "Update Your Profile and Other Stuff",
//             content: "Under this menu you can update settings and your profile.",
//             target: 'wh_tour_step3',
//             placement: "bottom"
//         },
//         {
//             title: "Date Filter For Everything",
//             content: "This date filter is available here. Try it, its magical and powerful.",
//             target: 'wh_tour_step4',
//             placement: "bottom"
//         },
//         {
//             title: "New Dashboard Menu",
//             content: "Click here for the new dashboard options.",
//             target: 'wh_tour_step5',
//             placement: "bottom"
//         }
//     ]
// };
//
// function launchGuidedTour(tour_type) {
//     if(tour_type == 'first_time') {
//         hopscotch.startTour(tour_first_time);
//     }
// }

/****
 * Dashboard Load Lead Details Common Function
 * *****/

function ajaxLoadDashboardLeadDetails(lead_id) {

    $(".leads-list tr").removeClass("selected-lead");
    $('#leads_list_lead_row_'+lead_id).addClass("selected-lead");

    $('#loader_dashboard_lead_details').show();
    $('#response_dashboard_lead_details').hide();

    $.ajax({
        url: '/leads/ajax/load-dashboard-lead-details?' +
        'lead_id=' + lead_id

    }).done(function (data) {
        $('#response_dashboard_lead_details').html(data);
        $('#loader_dashboard_lead_details').hide();
        $('#response_dashboard_lead_details').show();
        $('.datepicker').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            lang: 'en',
            scrollInput: false
        });
        $('.timepicker').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            lang: 'en',
            scrollInput: false
        });
        $(document).foundation();
    });

}

/****
 * Dashboard Load Lead List Common Function
 * **/

function ajaxLoadDashboardLeadsList(page) {
    console.log('ajaxLoadDashboardLeadsList');
    $('#loader_dashboard_leads_list').show();
    $('#response_dashboard_leads_list').html('');
    $('#response_dashboard_leads_list').hide();

    var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
    var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
    var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

    var leads_list_filter_lead_source = $('#leads_list_filter_lead_source').val();
    var leads_list_filter_lead_rating = $('#leads_list_filter_lead_rating').val();
    var leads_list_search_text = $('#leads_list_search_text').val();
    var leads_list_filter_assigned_to = $('#leads_list_filter_assigned_to').val();
    var leads_list_filter_campaign = $('#leads_list_filter_campaign').val();


    $.ajax({
        url: '/leads/ajax/load-dashboard-leads?' +
        'page=' + page +
        '&dashboard_filter_date_range=' + dashboard_filter_date_range +
        '&dashboard_filter_from_date=' + dashboard_filter_from_date +
        '&dashboard_filter_to_date=' + dashboard_filter_to_date +
        '&leads_list_filter_lead_rating=' + leads_list_filter_lead_rating +
        '&leads_list_search_text=' + leads_list_search_text +
        '&leads_list_filter_lead_source=' + leads_list_filter_lead_source +
        '&leads_list_filter_assigned_to=' + leads_list_filter_assigned_to +
        '&leads_list_filter_campaign=' + leads_list_filter_campaign

    }).done(function (data) {
        $('#response_dashboard_leads_list').html(data);
        $('#loader_dashboard_leads_list').hide();
        $('#response_dashboard_leads_list').show();
        $('.datepicker').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            lang: 'en',
            scrollInput: false
        });
        $('.timepicker').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            lang: 'en',
            scrollInput: false
        });
        $(document).foundation();
    });
}