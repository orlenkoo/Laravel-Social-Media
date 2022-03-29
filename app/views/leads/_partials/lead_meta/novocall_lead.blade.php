<?php $novocall_lead = $lead->novocallLead; ?>
@if (is_object($novocall_lead))
    <?php $novocall_lead_details = get_object_vars(json_decode($lead->novocallLead->lead_details)) ?>
    <ul>
        @foreach($novocall_lead_details as $key => $value)
            <li>
                <strong>{{ ucwords(str_replace('_', ' ', $key)) }} :</strong>
                <span>
                    {{ is_array($value)? implode(",", $value) : $value }}
                </span>
            </li>
        @endforeach
    </ul>
@else
    <p>Lead Not Found.</p>
@endif