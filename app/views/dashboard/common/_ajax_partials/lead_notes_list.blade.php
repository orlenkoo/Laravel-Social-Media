<div class="row">
    <div class="large-12 columns">


        @include('dashboard.common._partials.lead_notes_table')


    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <div id="pagination_dashboard_lead_notes_list">
            <?php echo $lead_notes->links(); ?>
        </div>
    </div>
</div>

