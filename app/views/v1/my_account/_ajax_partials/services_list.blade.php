<div class="row expanded">
    <div class="large-12 columns">

        @include('v1.my_account._partials.services_table')

    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <div id="pagination_event360_vendor_services_list">
            <?php echo $services->links(); ?>
        </div>
    </div>
</div>