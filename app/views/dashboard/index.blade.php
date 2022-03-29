@extends('layouts.default')

@section('content')
    @include('dashboard.'.$dashboard_type.'.dashboard_content')

    @include('my_activities._partials.add_new_activity_popup_forms', ['post_data_to_load' => 'dashboard_lead_time_line'])
    @include('my_activities._partials.edit_activity_popup_forms', ['post_data_to_load' => 'dashboard_lead_time_line'])




@stop