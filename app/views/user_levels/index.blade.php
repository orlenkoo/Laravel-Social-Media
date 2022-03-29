@extends('layouts.default')

@section('content')
    <div class="row page-title-bar">
        <div class="large-12 columns">
            <h1>User Levels</h1>
        </div>
    </div>

    <p><a href="#" data-reveal-id="newUserLevelForm" class="button tiny">Add New User Level</a></p>
    <div id="newUserLevelForm" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">

        <h2>Add New User Level</h2>
        {{ Form::open(array('route' => 'user_level.store','autocomplete' => 'off')) }}
        @include('user_levels._partials.form')
        {{ Form::close() }}
        <a class="close-reveal-modal" aria-label="Close">&#215;</a>

    </div>
    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <h5>Search</h5>
                {{ Form::text('s', null, array('placeholder' => 'Search by User Level', 'onchange' => 'searchUserLevel(this)')) }}
            </div>
        </div>
    </div>

    <script>
        function searchUserLevel(obj) {
            //alert(obj.value);
            document.getElementById('loader').style.display = 'block';
            document.getElementById('user_level_details').style.display = 'none';
            document.getElementById('user_level_details').innerHTML = '';

            var xmlhttp;
            if (window.XMLHttpRequest)
            {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            }
            else
            {// code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function()
            {
                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                    document.getElementById('loader').style.display = 'none';
                    document.getElementById('user_level_details').style.display = 'block';
                    document.getElementById("user_level_details").innerHTML=xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET","/user-levels/search?s="+obj.value,true);
            xmlhttp.send();


        }
    </script>





    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <h5>User Levels</h5>

                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader', 'style' => 'display:none')) }}

                @include('user_levels._ajax_partials.user_level_search_list_table')

            </div>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <?php echo $user_levels->links(); ?>
            </div>
        </div>
    </div>

@stop