<div class="">
 @include('quotations._partials.quotations_table')
</div>

<div class="panel-list-pagination" id="pagination_quotations_list">
    <?php echo $quotations->links(); ?>
</div>