<div class="row">
    <div class="large-12 columns">
            {{ Form::text('s', null, array('placeholder' => 'Search by Fields', 'onchange' => 'getAjaxEvent360WebsiteFormSubmissionList()', 'id' => 'event360_form_submission_search_query')) }}
        <hr>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
            <h5>Website Form Submissions</h5>
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_event360_website_form_submission_list', 'style' => 'display:none')) }}
            <div id="event360_website_form_submission_list">

            </div>
    </div>
</div>

<script>


    /*==================== PAGINATION =========================*/

    $(document).on('click', '#pagination_event360_website_form_submission_list a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        //location.hash = page;
        getAjaxEvent360WebsiteFormSubmissionList(page);
    });


    function getAjaxEvent360WebsiteFormSubmissionList(page) {

        $('#loader_event360_website_form_submission_list').show();
        $('#event360_website_form_submission_list').hide();
        $('#event360_website_form_submission_list').html('');

        var search_query = $('#event360_form_submission_search_query').val();

        $.ajax({
            url: '/leads-detail/ajax/get/event360-website-form-submissions?page=' + page + '&search_query=' + search_query
        }).done(function (data) {
            $('#event360_website_form_submission_list').html(data);
            $('#loader_event360_website_form_submission_list').hide();
            $('#event360_website_form_submission_list').show();
            $("#event360_website_form_submission_details").tablesorter();
            $('.datepicker').datetimepicker({
                format: 'Y-m-d',
                lang: 'en',
                scrollInput: false
            });
            $(document).foundation();
        });
    }

    getAjaxEvent360WebsiteFormSubmissionList(1);


</script>
