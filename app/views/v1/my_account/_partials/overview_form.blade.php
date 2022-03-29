{{ Form::model($organization, array('route' => array('event360.vendor_profile.overview.edit', $organization->id), 'method' => 'put', 'class' => 'form-horizontal')) }}

<div class="row expanded">

    <div class="large-12 columns">
        {{ Form::label('organization', 'Company Name *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('organization', $organization->organization, array('onchange' => '',  'data-validation'=>'required', 'disabled'=>'disabled')) }}</div>
        {{ $errors->first('organization', '<p class="alert-box alert radius">:message</p>') }}
    </div>



</div>

<div class="row expanded">
    <div class="large-6 columns">
        {{ Form::label('address_line_1', 'Address Line 1*', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('address_line_1', $organization->address_line_1, array('onchange' => '',  'data-validation'=>'required', 'disabled'=>'disabled')) }}</div>
        {{ $errors->first('address_line_1', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-6 columns">
        {{ Form::label('address_line_2', 'Address Line 2*', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('address_line_2', $organization->address_line_2, array('onchange' => '',  'data-validation'=>'required', 'disabled'=>'disabled')) }}</div>
        {{ $errors->first('address_line_2', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>

<div class="row expanded">
    <div class="large-4 columns">
        {{ Form::label('city', 'City *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('city', $organization->city, array('onchange' => '',  'data-validation'=>'required', 'disabled'=>'disabled')) }}</div>
        {{ $errors->first('city', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-4 columns">
        {{ Form::label('state', 'State', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('state', $organization->state, array('onchange' => '',  'data-validation'=>'', 'disabled'=>'disabled')) }}</div>
        {{ $errors->first('state', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-4 columns">
        {{ Form::label('country', 'Country', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('country', $organization->country, array('onchange' => '',  'data-validation'=>'', 'disabled'=>'disabled')) }}</div>
        {{ $errors->first('country', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::label('postal_code', 'Postal Code *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('postal_code', $organization->postal_code, array('onchange' => '',  'data-validation'=>'required', 'disabled'=>'disabled')) }}</div>
        {{ $errors->first('postal_code', '<p class="alert-box alert radius">:message</p>') }}
    </div>

</div>

<div class="row expanded">
    <div class="large-4 columns">
        {{ Form::label('phone_number_country_code', 'Phone Number Country Code', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('phone_number_country_code', $organization->phone_number_country_code, array('onchange' => '',  'data-validation'=>'', 'disabled'=>'disabled')) }}</div>
        {{ $errors->first('phone_number_country_code', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-4 columns">
        {{ Form::label('phone_number_area_code', 'Phone Number Area Code', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('phone_number_area_code', $organization->phone_number_area_code, array('onchange' => '',  'data-validation'=>'', 'disabled'=>'disabled')) }}</div>
        {{ $errors->first('phone_number_area_code', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-4 columns">
        {{ Form::label('phone_number', 'Phone Number *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('phone_number', $organization->phone_number, array('onchange' => '',  'data-validation'=>'required', 'disabled'=>'disabled')) }}</div>
        {{ $errors->first('phone_number', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>

<div class="row expanded">
    <div class="large-6 columns">
        {{ Form::label('email', 'Email', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('email', $organization->email, array('onchange' => '',  'data-validation'=>'required', 'disabled'=>'disabled')) }}</div>
        {{ $errors->first('email', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-6 columns">
        {{ Form::label('website_url', 'Website URL', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('website_url', $organization->website_url, array('onchange' => '',  'data-validation'=>'required', 'disabled'=>'disabled')) }}</div>
        {{ $errors->first('website_url', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>

<hr>

{{--<div class="row save_bar">--}}
    {{--<div class="large-12 columns text-center">--}}
        {{--{{ Form::submit('Save', array("class" => "button success tiny")) }}--}}

    {{--</div>--}}
{{--</div>--}}

{{--<div class="row loading_animation" style="display: none;">--}}
    {{--<div class="large-12 columns text-center">--}}
        {{--{{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}--}}
    {{--</div>--}}
{{--</div>--}}



{{ Form::close() }}