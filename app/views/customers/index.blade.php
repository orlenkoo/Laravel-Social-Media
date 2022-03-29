@extends('layouts.default')

@section('content')

    <div class="row expanded">
        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-content">
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="search_query">
                                Search For
                                {{ Form::text('search_query', null, array('placeholder' => 'Search By Customer Name / Website / Phone', 'id' => 'search_query', 'onchange'=>'loadDashboardData()')) }}
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="customer_tags">
                                Filter By Customer Tags
                                <select name="customer_tags" id="search_customer_tags" placeholder="Tags" onchange="loadDashboardData()" multiple>
                                    <?php
                                    $tags = CustomersController::getTagsForOrganization();
                                    foreach($tags as $tag){
                                        $id = $tag['id'];
                                        $tag = $tag['tag'];
                                        echo "<option value='$id'>$tag</option>";
                                    }
                                    ?>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-6 columns text-right">
                            <input type="button" value="Clear Filters" class="alert button tiny" style="width: 100%;" onclick="clearLeadsFilter()">
                        </div>
                        <div class="large-6 columns text-right">
                            <input type="button" class="button tiny success" value="Search" style="margin-top: 0px; width: 100%;" onclick="loadDashboardData()">
                        </div>
                    </div>

                    <script>
                        function clearLeadsFilter() {
                            $('#search_query').val('');
                            $('#customer_tags').val('');
                            loadDashboardData();
                        }
                    </script>
                </div>
            </div>
        </div>
        <div class="large-9 columns">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row expanded">
                        <div class="large-6 columns">
                            <h1>Customers</h1>
                        </div>
                        <div class="large-6 columns">

                        </div>
                    </div>
                </div>

                <div class="panel-content">

                    <div class="row expanded">
                        <div class="large-12 columns">
                            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_customers', 'class' => 'float-center', 'style' => 'display:none')) }}
                            <div id="response_customers">

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>

        function loadDashboardData() {
            ajaxLoadCustomers(1);

        }

        loadDashboardData();

        /*==================== PAGINATION =========================*/

        $(document).on('click', '#pagination_customers_list a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            //location.hash = page;
            ajaxLoadCustomers(page);
        });

        function ajaxLoadCustomers(page) {

            $('#loader_customers').show();
            $('#response_customers').hide();

            var search_query = $("#search_query").val();
            var search_customer_tags = $("#search_customer_tags").val();
            var dashboard_filter_date_range = $('#dashboard_filter_date_range').val();
            var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
            var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

            $.ajax({
                url: '/customers/ajax/load-customers?' +
                'page=' + page +
                '&search_query=' + encodeURIComponent(search_query) +
                '&search_customer_tags=' + search_customer_tags +
                '&dashboard_filter_date_range=' + dashboard_filter_date_range +
                '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                '&dashboard_filter_to_date=' + dashboard_filter_to_date

            }).done(function (data) {

                $('#response_customers').html(data);
                $('#loader_customers').hide();
                $('#response_customers').show();
                $(document).foundation();
                setCustomerTags();

            });
        }


        function assignCustomerTags(tag_id,customer_id){
            var customer_tags = $("#"+tag_id+"").val();

            $.post("/customers/ajax/assign-customer-tags",
                    {
                        customer_id: customer_id,
                        customer_tags: customer_tags
                    },
                    function (response, status) {
                        $.notify(response, "success");
                    });
        }

        function setCustomerTags(){
            $(".customer_tags").selectize({
                maxItems: null,
                valueField: 'id',
                labelField: 'tag',
                searchField: ['tag', 'id'],
                render: {
                    item: function (item, escape) {
                        return '<div>' +
                            (item.tag ? '<span class="tag">' + escape(item.tag) + '</span>' : '') +
                            (item.tag ? '' : '<span class="id">' + escape(item.id) + '</span>') +
                            '</div>';
                    },
                    option: function (item, escape) {
                        var label = item.tag || item.id;
                        var caption = '';
                        return '<div>' +
                            '<span class="label">' + escape(label) + '</span>' +
                            (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
                            '</div>';
                    }
                },
                createFilter: function (input) {
                    return true;
                },
                create: function (input) {
                    return {id: input};
                }
            });
        }

        $(document).ready(function() {

            $('#search_customer_tags').selectize({
                create: false,
                sortField: 'text'
            });

            setCustomerTags();
        });
        
    </script>

@stop