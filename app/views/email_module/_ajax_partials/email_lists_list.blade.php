@include('email_module._partials.email_lists_table')


<div class="panel-list-pagination" id="pagination_email_lists_list">
    <?php echo $email_lists->links(); ?>
</div>