<div class="row expanded">
    <div class="large-12 columns">

        @include('v1.my_account._partials.testimonials_table')

    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <div id="pagination_event360_vendor_testimonial_list">
            <?php echo $testimonials->links(); ?>
        </div>
    </div>
</div>