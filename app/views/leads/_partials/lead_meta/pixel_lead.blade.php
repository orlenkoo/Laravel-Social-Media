@if(is_object($lead->web360Enquiry))
    <?php $enquiry_details = json_decode($lead->web360Enquiry->enquiry_details); ?>
    @if (is_object($enquiry_details))
        <ul>
            @foreach($enquiry_details as $key => $value)
                <li>
                    <strong>{{ ucwords(str_replace('_', ' ', $key)) }} :</strong>
                    <span>
                        {{ is_array($value)? implode(",", $value) : $value }}
                    </span>
                </li>
            @endforeach
        </ul>
    @else
        <p>{{ $enquiry_details }}</p>
    @endif
@else
    <p>Lead Not Found.</p>
@endif