<?php $enquiry_details = json_decode($lead->web360Enquiry->enquiry_details); ?>
@if (is_object($enquiry_details))
    <table>
        @foreach($enquiry_details as $key => $value)
        <tr>
            <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
            <td>
                {{ is_array($value)? implode(",", $value) : $value }}
            </td>
        </tr>
        @endforeach
    </table>
@else
    <p>{{ $enquiry_details }}</p>
@endif