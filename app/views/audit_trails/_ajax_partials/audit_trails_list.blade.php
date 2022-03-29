<div class="row expanded">
    <div class="large-12 columns">
        @include('audit_trails._partials.audit_trails_table')
    </div>
</div>

<div class="panel-list-pagination" id="pagination_audit_trails_list">
    <?php echo $audit_trail_employees->links(); ?>
</div>