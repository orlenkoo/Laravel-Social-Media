<div class="row">
    <div class="large-12 columns">
        {{ Form::text('search_by', null, array('placeholder' => 'Search Contacts By Given Name / Surname / Phone / Email / Designation', 'id' => 'search_by', 'onchange' => 'ajaxLoadContactsList()')) }}
    </div>
</div>


        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_contacts_list', 'class' => 'float-center', 'style' => 'display:none')) }}
        <div id="response_contacts_list">

        </div><!--end response_contacts_list-->
        <script>
            /*==================== PAGINATION =========================*/

            $(document).on('click', '#pagination_contacts_list a', function (e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                //location.hash = page;
                ajaxLoadContactsList(page);
            });

            function ajaxLoadContactsList(page) {
                $('#loader_contacts_list').show();
                $('#response_contacts_list').hide();

                var search_by = $("#search_by").val();

                $.ajax({
                    url: '/contacts/ajax/load-contacts-list?' +
                    'page=' + page +
                    '&screen=dashboard' +
                    '&customer_id={{ $customer->id }}' +
                    '&search_by=' + search_by

                }).done(function (data) {
                    $('#response_contacts_list').html(data);
                    $('#loader_contacts_list').hide();
                    $('#response_contacts_list').show();
                    $(document).foundation();
                });
            }

            ajaxLoadContactsList(1);
            
        </script>


        <div class="row">
            <div class="large-12 columns">
                <h4>Add Contact</h4>
            </div>
        </div>
        @include('contacts._partials.add_new_contact_form')
