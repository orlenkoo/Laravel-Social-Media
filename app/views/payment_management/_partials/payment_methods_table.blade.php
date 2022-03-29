<div class="row expanded small-up-1 medium-up-2 large-up-3">
    @foreach($payment_methods as $payment_method)
        <div class="column">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="large-6 columns">
                            <h4>Card Type: {{ $payment_method->credit_card_type }}</h4>
                        </div>
                        <div class="large-6 columns text-right">
                            {{ $payment_method->primary_card == 1? "PRIMARY CARD": "" }}
                        </div>
                    </div>

                </div>
                <div class="panel-content">
                    <div class="row">
                        <div class="large-12 columns">
                            <p>{{ $payment_method->getDisplayCardNumber() }}</p>
                            <em>Expires On: {{ $payment_method->expiry_month }} / {{ $payment_method->expiry_year }}</em>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row save_bar">
                        <div class="large-8 columns">
                            &nbsp;
                        </div>
                        <div class="large-2 columns text-right">
                            &nbsp;
                        </div>
                        <div class="large-2 columns text-right">
                            <button class="button tiny alert pull-right" type="button" data-open="popup_update_payment_method_form_{{ $payment_method->id }}">Edit</button>
                            <div class="reveal small panel" id="popup_update_payment_method_form_{{ $payment_method->id }}" name="popup_update_payment_method_form_{{ $payment_method->id }}" data-reveal>
                                @include('payment_management._partials.payment_method_form_edit')
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>