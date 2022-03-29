<div class="row expanded">
    <div class="large-12 columns">
        @include('payment_management._partials.payment_methods_table')
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <div id="pagination_payment_methods_list">
            <?php echo $payment_methods->links(); ?>
        </div>
    </div>
</div>

