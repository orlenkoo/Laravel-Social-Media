@include('email_module._partials.selected_contacts_table')


<div class="panel-list-pagination" id="pagination_selected_contacts_list">
    <?php echo $contacts->links(); ?>
</div>