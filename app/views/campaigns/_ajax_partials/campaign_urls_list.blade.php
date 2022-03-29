@include('campaigns._partials.campaign_urls_table')


<div class="panel-list-pagination" id="pagination_campaign_urls_list_{{ $campaign_id }}">
    <?php echo $campaign_urls->links(); ?>
</div>