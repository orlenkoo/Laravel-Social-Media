<script>
    function updateAjaxLeadRating(lead_id, lead_rating) {
        var r = confirm("Would you like to Update this Lead Rating to " + lead_rating + "?");
        if (r == true) {
            $.post("/event360-leads/ajax/update-rating/", {lead_id: lead_id, lead_rating: lead_rating}, function (data) {
                $.notify('Updated Successfully.','success');
                //alert(data);
                //getAjaxWeb360EnquiryLeadsList(1);
            });
        } else {
            return false;
        }
    }

    function updateAjaxLeadStatus(lead_id, lead_source) {
        var r = confirm("Would you like to Accept this Lead?");
        if (r == true) {
            $.post("/event360-leads/ajax/update-status/", {lead_id: lead_id}, function (data) {
                $.notify('Updated Successfully.','success');
                //alert(data);
                if (lead_source == 'event360_enquiries') {
                    getAjaxEvent360EnquiryLeadsList(1);
                } else if (lead_source == 'web360_enquiries') {
                    getAjaxWeb360EnquiryLeadsList(1);
                } else if (lead_source == 'event360_messenger_threads') {
                    getAjaxEvent360MessengerThreadLeadsList(1);
                }


            });
        } else {
            return false;
        }
    }

    function clearFilter() {
        $('#filter_enquiries_lead_status').val('');
        $('#filter_enquiries_lead_rating').val('');
        $('#filter_enquiries_lead_from_date').val('');
        $('#filter_enquiries_lead_to_date').val('');


        // get the customer list after clearing filter
        getAjaxEvent360EnquiryLeadsList(1);
    }

    function clearFilterWeb360() {
        $('#filter_web360_enquiries_lead_status').val('');
        $('#filter_web360_enquiries_lead_rating').val('');
        $('#filter_web360_enquiries_lead_from_date').val('');
        $('#filter_web360_enquiries_lead_to_date').val('');


        // get the customer list after clearing filter
        getAjaxWeb360EnquiryLeadsList(1);
    }

    function clearFilterCallTracking() {
        $('#filter_call_tracking_from_date').val('');
        $('#filter_call_tracking_to_date').val('');


        // get the customer list after clearing filter
        getAjaxCallTrackingLeadsList(1);
    }

    function clearFilterEvent360MessengerThread() {
        $('#filter_event360_messenger_threads_lead_status').val('');
        $('#filter_event360_messenger_threads_lead_rating').val('');
        $('#filter_event360_messenger_threads_lead_from_date').val('');
        $('#filter_event360_messenger_threads_lead_to_date').val('');


        // get the customer list after clearing filter
        getAjaxEvent360MessengerThreadLeadsList(1);
    }


</script>
