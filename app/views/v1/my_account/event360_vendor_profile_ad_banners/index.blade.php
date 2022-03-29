<div class="row expanded">
    <div class="large-12 columns">
        <p><a href="#" data-open="newAdBannerForm" class="button tiny">Add New Ad Banner</a></p>
        <div id="newAdBannerForm" class="reveal" data-reveal>
            <h2>Add New Ad Banner</h2>
            {{ Form::open(array('route' => 'event360.vendor_profile.ad_banner.add', 'files' => true,'autocomplete' => 'off')) }}
            @include('v1.my_account._partials.event360_vendor_profile_ad_banners_form')
            {{ Form::close() }}
            <button class="close-button" data-close aria-label="Close modal" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>
<div class="row expanded">
    <div class="large-12 columns">
            {{ Form::text('s', null, array('placeholder' => 'Search by Ad Title', 'onchange' => 'getAjaxAdBannersList()', 'id' => 'ad_banners_search_query')) }}
        <hr>
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">

            <h5>Ad Banners</h5>
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_ad_banners_list', 'style' => 'display:none')) }}
            <div id="ad_banners_list">

            </div>

    </div>
</div>

<script>


    /*==================== PAGINATION =========================*/

    $(document).on('click', '#pagination_event360_vendor_profile_ad_banners_list a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        //location.hash = page;
        getAjaxAdBannersList(page);
    });


    function getAjaxAdBannersList(page) {


        $('#loader_ad_banners_list').show();
        $('#ad_banners_list').hide();
        $('#ad_banners_list').html('');

        var search_query = $('#ad_banners_search_query').val();


        $.ajax({
            url: '/event360-vendor-profile/ajax/get/ad-banners?page=' + page + '&search_query=' + search_query + '&event360_vendor_profile_id=<?php echo $event360_vendor_profile->id; ?>'
        }).done(function (data) {
            $('#ad_banners_list').html(data);
            $('#loader_ad_banners_list').hide();
            $('#ad_banners_list').show();
            $("#event360_vendor_profile_ad_banners_details").tablesorter();

            $('.datepicker-webclickz-timepicker').datetimepicker({
                format: 'yyyy-mm-dd hh:ii',
                disableDblClickSelection: true,
                language: 'vi',
                pickTime: true
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

    getAjaxAdBannersList(1);
</script>
