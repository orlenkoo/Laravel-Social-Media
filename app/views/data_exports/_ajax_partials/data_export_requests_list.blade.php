<div class="">
 @include('data_exports._partials.data_export_requests_table')
</div>

<div class="panel-list-pagination" id="pagination_data_export_requests_list">
    <?php echo $data_export_requests->links(); ?>
</div>