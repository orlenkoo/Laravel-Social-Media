@include('email_module._partials.email_campaigns_table')


<div class="panel-list-pagination" id="pagination_email_campaigns_list">
    <?php echo $email_campaigns->links(); ?>
</div>