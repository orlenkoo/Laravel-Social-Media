<div class="row expanded">
    <div class="large-12 columns">
    <p><a href="#" data-open="newServiceForm" class="button tiny">Add New Service</a></p>
    <div id="newServiceForm" class="reveal" data-reveal>
        <h2>Add New Service</h2>
        {{ Form::open(array('route' => 'event360.vendor_profile.services.add', 'files' => true,'autocomplete' => 'off')) }}
        @include('v1.my_account._partials.services_form')
        {{ Form::close() }}
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    </div>
</div>
<div class="row expanded">
    <div class="large-12 columns">


            {{ Form::text('s', null, array('placeholder' => 'Search by Service', 'onchange' => 'getAjaxServicesList()', 'id' => 'services_search_query')) }}
        <hr>
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">

            <h5>Services</h5>
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_services_list', 'style' => 'display:none')) }}
            <div id="services_list">

            </div>

    </div>
</div>

<script>


    /*==================== PAGINATION =========================*/

    $(document).on('click', '#pagination_event360_vendor_services_list a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        //location.hash = page;
        getAjaxServicesList(page);
    });


    function getAjaxServicesList(page) {


        $('#loader_services_list').show();
        $('#services_list').hide();
        $('#services_list').html('');

        var search_query = $('#services_search_query').val();


        $.ajax({
            url: '/event360-vendor-profile/ajax/get/services?page=' + page + '&search_query=' + search_query + '&event360_vendor_profile_id=<?php echo $event360_vendor_profile->id; ?>'
        }).done(function (data) {
            $('#services_list').html(data);
            $('#loader_services_list').hide();
            $('#services_list').show();
            $("#event360_vendor_services_details").tablesorter();
            $('.datepicker').datetimepicker({
                format: 'Y-m-d',
                lang: 'en',
                scrollInput: false
            });
            $.validate({
                onSuccess: function () {
                    $('.save_bar').css('display', 'none');
                    $('.loading_animation').css('display', 'block');
                },
            });
            $(document).foundation();
        });
    }

    getAjaxServicesList(1);
</script>
