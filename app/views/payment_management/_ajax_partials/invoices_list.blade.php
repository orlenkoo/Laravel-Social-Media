<div class="row expanded">
    <div class="large-12 columns">
        @include('payment_management._partials.invoices_table')
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <div id="pagination_invoices_list">
            <?php echo $invoices->links(); ?>
        </div>
    </div>
</div>

