{{--@if(!Session::get('user-selected-the-layout-once') && Session::get('user-use-old-layout'))--}}
    <div class="row expanded">
        <div class="large-12 columns">
            <div class="callout success">
                <h5>Dashboard Upgraded</h5>
                <p>You can continue to use the Old Dashboard or find out whats new in the New Dashboard.</p>
                <div class="button-group">
                    <a class="tiny button success" onclick="ajaxUpdateLayoutPreference('new_layout');">Checkout the New Dashboard</a>
                </div>
            </div>
        </div>
    </div>
{{--@endif--}}

<script>
    function ajaxUpdateLayoutPreference(layout_preference) {
        $.ajax({
            url: '/employees/ajax-update-layout-preference?' +
            'layout_preference=' + layout_preference

        }).done(function (data) {
            $.notify('Successfully Updated', 'success');
            if(layout_preference == 'new_layout') {
                window.location.replace("{{ route('home') }}");
            }
        });
    }
</script>