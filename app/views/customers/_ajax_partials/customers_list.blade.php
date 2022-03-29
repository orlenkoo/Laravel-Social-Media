<div class="">
    @include('customers._partials.customers_table')
</div>

<div class="panel-list-pagination" id="pagination_customers_list">
    <?php echo $customers->links(); ?>
</div>