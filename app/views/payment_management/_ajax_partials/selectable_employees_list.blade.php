<div class="row expanded">
    <div class="large-12 columns">
        @include('payment_management._partials.selectable_employees_table')
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <div id="pagination_selectable_employees_list">
            <?php echo $employees->links(); ?>
        </div>
    </div>
</div>

