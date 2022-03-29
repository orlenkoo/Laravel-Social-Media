<script src="{{ asset('assets/js/vendor/what-input.js') }}"></script>
<script src="{{ asset('assets/js/vendor/foundation.js') }}"></script>
<script src="{{ asset('assets/js/vendor/jquery.datetimepicker.full.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/notify.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/selectize.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.form-validator.min.js') }}"></script>

{{--hopscotch guided tour library--}}
{{--http://linkedin.github.io/hopscotch/--}}
{{--https://github.com/linkedin/hopscotch--}}
<script src="{{ asset('assets/js/hopscotch.min.js') }}"></script>

{{--this file contains all the javascript functions--}}
<script src="{{ asset('assets/js/app.js') }}"></script>



{{--@if(Route::currentRouteName() == 'home' && Session::get('user-took-the-guide') == 0)--}}
{{--<script>--}}
    {{--// Start the tour!--}}
{{--//    launchGuidedTour('first_time');--}}
{{--</script>--}}
{{--@endif--}}

<script>
    window.addEventListener( "pageshow", function ( event ) {
  var historyTraversal = event.persisted || 
                         ( typeof window.performance != "undefined" && 
                              window.performance.navigation.type === 2 );
  if ( historyTraversal ) {
    // Handle page restore.
    window.location.reload();
  }
});
</script>

</body>
</html>