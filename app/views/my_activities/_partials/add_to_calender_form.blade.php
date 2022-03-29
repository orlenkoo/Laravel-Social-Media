{{ Form::open(array('route' => 'my_activities.add_activity_to_my_calender','target' => '_blank','autocomplete' => 'off')) }}
<input type="hidden" name="calender_data" value = "<?= htmlspecialchars(json_encode(MyActivitiesController::generateJSONForCalendar($activity_type,$activity_object))); ?>">
<div class="row">
        <h3>Add to Calender</h3>
</div>
<div class="row">
    <div class="large-6 columns">
        <label for="calender_type">
            Calender Type
        </label>
        <input type="radio" name="calender_type" value="apple_calender"> Apple Calender<br>
        <input type="radio" name="calender_type" value="google_online"> Google (online)<br>
        <input type="radio" name="calender_type" value="outlook">Outlook
    </div>
</div>

<div class="row save_bar">
    <div class="large-10 columns">
        &nbsp;
    </div>
    <div class="large-2 columns text-right">
        {{ Form::submit('Add', array("class" => "button success tiny")) }}
    </div>
</div>

<div class="row loading_animation" style="display: none;">
    <div class="large-12 columns text-center">
        {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
    </div>
</div>
{{Form::close()}}