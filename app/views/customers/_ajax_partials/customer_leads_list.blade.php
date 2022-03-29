<div class="">
    @include('customers._partials.customer_leads_table')
</div>

<div class="panel-list-pagination" id="pagination_customer_leads_list">
    <?php echo $customer_leads->links(); ?>
</div>