<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web360</title>

    <link href="{{ asset('assets/img/favicon.png') }}" rel="shortcut icon" type="image/x-icon">

    <!--Google Charts-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});
    </script>

    <link rel="stylesheet" href="{{ asset('assets/css/foundation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.datetimepicker.css') }}">
    <link href='{{ asset('assets/css/selectize.css') }}' rel='stylesheet' type='text/css'>
    <script src="{{ asset('assets/js/vendor/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.tablesorter.min.js') }}"></script>

    <!-- dropzone fle uploader -->
    <script src="{{ asset('assets/js/dropzone.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.css') }}"/>

    {{--CKEDITOR--}}
    <script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>

    <!-- ttwMusicPlayer -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/ttwMusicPlayer/css/style.css') }}">
    <script type="text/javascript" src="{{ asset('assets/js/ttwMusicPlayer/jquery-jplayer/jquery.jplayer.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/ttwMusicPlayer/ttw-music-player-min.js') }}"></script>

    <!-- Full Calender -->
    <link rel='stylesheet' type="text/css" href='{{ asset('assets/css/fullcalendar/fullcalendar.css') }}' />
    <script type="text/javascript" src='{{ asset('assets/js/lib/moment.min.js') }}'></script>
    <script type="text/javascript" src='{{ asset('assets/js/fullcalendar/fullcalendar.js') }}'></script>

    {{-- Grape HTML Editor --}}
    <link rel="stylesheet" href="{{ asset('assets/css/grapes.min.css') }}">
    <script src="{{ asset('assets/js/grapes.min.js') }}"></script>
    <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet">
    <script src="https://unpkg.com/grapesjs"></script>
    <script src="{{ asset('assets/js/grapesjs-blocks-basic.min.js') }}"></script>
    <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/grapesjs-preset-webpage.min.css') }}" rel="stylesheet">
    {{--<script src="https://feather.aviary.com/imaging/v3/editor.js"></script>--}}
    <script src="https://static.filestackapi.com/v3/filestack-0.1.10.js"></script>
    <script src="https://unpkg.com/grapesjs"></script>
    <script src="{{ asset('assets/js/grapesjs-preset-webpage.min.js') }}"></script>

    {{-- tippy the tool tip library --}}
    {{--https://atomiks.github.io/tippyjs/--}}
    <script src="https://unpkg.com/tippy.js@2.4.1/dist/tippy.all.min.js"></script>

    {{--hopscotch guided tour library--}}
    {{--http://linkedin.github.io/hopscotch/--}}
    {{--https://github.com/linkedin/hopscotch--}}
    {{--<link rel="stylesheet" href="{{ asset('assets/css/hopscotch.min.css') }}">--}}

    {{-- put all css libraries above this line--}}

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">


    <!-- AddEvent Button-->
    <link rel="stylesheet" href="{{ asset('assets/css/addevent/css/theme6.css') }}" type="text/css" media="screen" />
    <script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js"></script>




</head>
<body>