 @include('campaigns._partials.campaigns_table')


<div class="panel-list-pagination" id="pagination_campaigns_list">
    <?php echo $campaigns->links(); ?>
</div>