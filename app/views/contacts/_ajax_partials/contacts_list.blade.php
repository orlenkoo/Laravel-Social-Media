<div class="row">
    <div class="large-12 columns">
        @include('contacts._partials.contacts_table')
    </div>
</div>


<div class="row">
    <div class="large-12 columns">
        <div id="pagination_dashboard_contacts_list">
            <?php echo $contacts->links(); ?>
        </div>
    </div>
</div>

