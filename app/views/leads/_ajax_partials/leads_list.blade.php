<div class="">
    @include('leads._partials.leads_table')
</div>

<div class="panel-list-pagination" id="pagination_leads_list">
    <?php echo $leads->links(); ?>
</div>