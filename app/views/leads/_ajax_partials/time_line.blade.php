@foreach($customer_time_line_items as $customer_time_line_item)
        <div class="row timeline_item">
                <div class="large-1 columns">
                        <div class="timeline_step_marker {{ CustomerTimeLineItem::$customer_time_line_classes[$customer_time_line_item->time_line_item_source] }}"></div>
                </div>
                <div class="large-3 columns">
                        {{ $customer_time_line_item->datetime }}
                </div>
                <div class="large-4 columns">
                        Activity Type: {{ $customer_time_line_item->time_line_item_source }}
                </div>
                <div class="large-4 columns">

                    @if($customer_time_line_item->time_line_item_source == 'Call')
                        <input type="button"  class="button tiny align-right" data-open="popup_edit_call" value="View Details" onclick="ajaxGetEditCallForm({{ $customer_time_line_item->time_line_item_source_id }})">
                    @elseif($customer_time_line_item->time_line_item_source == 'Email')
                        <input type="button"  class="button tiny align-right" data-open="popup_edit_email" value="View Details" onclick="ajaxGetEditEmailForm({{ $customer_time_line_item->time_line_item_source_id }})">
                    @elseif($customer_time_line_item->time_line_item_source == 'Meeting')
                        <input type="button"  class="button tiny align-right" data-open="popup_edit_meeting" value="View Details" onclick="ajaxGetEditMeetingForm({{ $customer_time_line_item->time_line_item_source_id }})">
                    @elseif($customer_time_line_item->time_line_item_source == 'Quotation')
                        <input type="button"  class="button tiny align-right" data-open="reveal_edit_quotation_{{ is_object($customer_time_line_item->quotation)? $customer_time_line_item->quotation->id: null }}" value="View Details">
                        <div class="reveal panel @if($customer_time_line_item->time_line_item_source == 'Quotation') {{ 'large' }}@endif " id="reveal_edit_quotation_{{ is_object($customer_time_line_item->quotation)? $customer_time_line_item->quotation->id: null }}" name="reveal_edit_activity_lead" data-reveal>
                            @include('quotations._partials.edit_quotation_form', [
                                                'quotation' => is_object($customer_time_line_item->quotation)? $customer_time_line_item->quotation: null,
                                                'quotation_use_for' => 'leads_time_line'
                                                ])
                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    @endif






                </div>
        </div>
@endforeach

<div class="panel-list-pagination" id="pagination_lead_time_line">
    <?php echo $customer_time_line_items->links(); ?>
</div>

<script>
    function closeRevealEditModal(){
        $('[name="reveal_edit_activity_lead"]').foundation('close');
    }
</script>

