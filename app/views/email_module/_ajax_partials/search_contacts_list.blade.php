@include('email_module._partials.search_contacts_table')


<div class="panel-list-pagination" id="pagination_search_contacts_list">
    <?php echo $contacts->links(); ?>
</div>