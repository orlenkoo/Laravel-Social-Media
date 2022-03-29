@extends('layouts.v1_layout')

@section('content')

<div class="row page-title-bar">
    <div class="large-12 columns">
        <h1>Leads Management - Event360 Online Lead</h1>
    </div>
</div>

<div class="row expanded">
    <div class="large-6 columns">
        {{ link_to_route('leads.index', '< Back to Lead Management', null, array("class" => 'button tiny success')) }}
    </div>
</div>

@if($lead->status == 'Accepted')

<div class="panel">
    <div class="row expanded">
        <div class="large-12 columns">

            <h1>Manage Lead</h1>

            <div class="row expanded">
                <div class="large-3 columns">
                    {{ Form::label('filter_event360_enquiries_lead_rating', 'Lead Rating', array('class'=>'control-label')) }}
                    <select name="" id="" onchange="updateAjaxLeadRating({{ $lead->id }}, this.value)" <?php echo $lead->status == 'Pending' ? "disabled": ""; ?>>
                        @foreach(Lead::$lead_ratings as $lead_rating)
                            <option value="{{ $lead_rating }}" <?php echo $lead->lead_rating == $lead_rating ? "selected" : ""; ?>>{{ $lead_rating }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="large-2 columns left">
                    {{ Form::label('filter_event360_enquiries_lead_foward', 'Lead Foward', array('class'=>'control-label')) }}
                    <a href="#" data-open="forward_lead_event360_enquiry_details_{{ $lead->id }}" class="button tiny">Forward ></a>

                    <div class="reveal" id="forward_lead_event360_enquiry_details_{{ $lead->id }}" data-reveal>
                        <h2>Forward Lead Details</h2>
                        @include('v1.web360.lead_management._partials.forward_lead')
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="large-2 columns left">
                    {{ Form::label('filter_event360_enquiries_lead_notes', 'Lead Notes', array('class'=>'control-label')) }}
                    <a href="#" data-open="lead_notes_event360_enquiry_details_{{ $lead->id }}" class="button tiny">Notes ></a>

                    <div class="reveal" id="lead_notes_event360_enquiry_details_{{ $lead->id }}" data-reveal>
                        <h2>Lead Notes</h2>
                        @include('v1.web360.lead_management._partials.lead_notes')
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

<div class="panel">
    @include('v1.web360.lead_management._partials.event360_quote_for_lead')
</div>

@include('v1.web360.lead_management._partials.leads_enquiries_javascript_functions')
@stop