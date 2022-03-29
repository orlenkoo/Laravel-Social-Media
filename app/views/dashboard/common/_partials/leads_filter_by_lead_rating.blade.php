<input type="hidden" id="leads_list_filter_lead_rating" name="leads_list_filter_lead_rating">
<ul class="legends-list">
    <li><span class="panel-list-item-status-ball"></span><a onclick="filterLeadsByLeadRating('')"> All</a></li>
    @foreach(Lead::$lead_ratings_classes as $key => $value)
        <li><span class="panel-list-item-status-ball {{ $value }}">&nbsp;</span><a onclick="filterLeadsByLeadRating('{{ $key }}')"> {{ $key }}</a></li>
    @endforeach
</ul>
<script>
    function filterLeadsByLeadRating(lead_rating) {
        $('#leads_list_filter_lead_rating').val(lead_rating);
        ajaxLoadDashboardLeadsList(1);
    }
</script>