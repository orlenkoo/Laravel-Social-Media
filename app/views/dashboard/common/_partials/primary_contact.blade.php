@if(is_object($customer))
    <?php $primary_contact = Contact::getPrimaryContact($customer->id) ?>
    @if(is_object($primary_contact))
    <p><img src="{{ asset('assets/img/icons/icon-dashboard-email.png') }}" style="width: 25px;" alt=""> : <span id="primary_contact_email_link">{{ is_object($primary_contact)? $primary_contact->email: '' }}</span></p>
    <p><img src="{{ asset('assets/img/icons/icon-dashboard-phone.png') }}" style="width: 25px;" alt=""> : <span id="primary_contact_phone_link">{{ is_object($primary_contact)? $primary_contact->phone_number: '' }}</span></p>
    @else
        <div class="callout warning small">
            <p>Primary Contact Not Selected</p>
        </div>
    @endif
    <p><img src="{{ asset('assets/img/icons/icon-dashboard-address.png') }}" style="width: 25px;" alt=""> : <span id="primary_contact_address_link">{{ $customer->getAddress() }}</span></p>
@endif