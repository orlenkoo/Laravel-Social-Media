@if(!is_object($lead->customer))

    @include('dashboard.common._partials.change_customer_for_lead', array('popup' => false))

    <div class="callout warning small" id="message_customer_not_present">
        <h5>Customer Not Present.</h5>
        <p>Enter Company Name to create or select existing customer.</p>
    </div>

@else
    <div class="row">
        <div class="large-9 columns">
            <label for="">
                Customer Name / Company Name
                <input type="text" id="dashboard_customer_name"
                       id="dashboard_customer_name"
                       value="{{ $lead->customer->customer_name }}" autocomplete="off" readonly>
            </label>
        </div>
        <div class="large-3 columns">
            <input type="button" value="Change Customer" class="tiny button warning" style="margin-top: 30px;"  data-open="popup_change_customer_for_lead">
            <div class="reveal" id="popup_change_customer_for_lead" data-reveal>
                <div class="row">
                    <div class="large-12 columns">
                        <h4>Change Customer</h4>
                        <hr>
                    </div>
                </div>
                @include('dashboard.common._partials.change_customer_for_lead', array('popup' => true))
                <button class="close-button" data-close aria-label="Close modal" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

        </div>
    </div>
    @include('customers._partials.customer_details_form', array('customer' => $lead->customer))
@endif