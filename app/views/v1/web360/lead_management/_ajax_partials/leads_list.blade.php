<div class="row expanded">
    <div class="large-12 columns">


        @include('v1.web360.lead_management._partials.leads_table')


    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <div id="pagination_leads_list">
            <?php echo $leads->links(); ?>
        </div>
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">

        {{ Form::open(array('route' => 'event360_leads.export_to_excel','autocomplete' => 'off')) }}
        {{ Form::hidden('leads_id_list_for_excel', json_encode($leads_id_list_for_excel)) }}
        {{ Form::hidden('lead_source', 'event360_enquiries') }}
        {{ Form::submit('Export', array('class' => 'tiny button')) }}
        {{ Form::close() }}

    </div>
</div>