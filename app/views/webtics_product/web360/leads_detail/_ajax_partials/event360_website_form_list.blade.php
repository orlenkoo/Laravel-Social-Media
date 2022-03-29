<div class="row">
    <div class="large-12 columns">

        @include('webtics_product.web360.leads_detail._partials.event360_website_form_table')

    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <div id="pagination_event360_website_form_submission_list">
            <?php echo $leads->links(); ?>
        </div>
    </div>
</div>