<table>
    <tr>
        <th>Name</th>
        <th>Subject</th>
        <th>to</th>
        <th>cc</th>
        <th>bcc</th>
        <th>Status</th>
        <th></th>
    </tr>
    @foreach($email_templates as $email_template)
        <tr>
           <td>{{ $email_template->name }}</td>
           <td>{{ $email_template->subject }}</td>
           <td>{{ $email_template->to }}</td>
           <td>{{ $email_template->cc }}</td>
           <td>{{ $email_template->bcc }}</td>
            <td>
                <select name="email_template_status_{{ $email_template->id }}" id="email_template_status_{{ $email_template->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('email_templates', '{{ $email_template->id }}', 'status', this.value, 'email_template_status_{{ $email_template->id }}', 'EmailTemplate')">
                    <option value="0" {{ $email_template->status == 0? "selected": "" }}>Disabled</option>
                    <option value="1" {{ $email_template->status == 1? "selected": "" }}>Enabled</option>
                </select>
            </td>
            <td>
                <button class="button tiny float-right" type="button" data-open="reveal_edit_email_template_{{ $email_template->id}}">Edit</button>
                <div class="reveal large panel" id="reveal_edit_email_template_{{ $email_template->id}}" name="reveal_add_email_template" data-reveal>

                        @include('settings.email_templates._partials.edit_new_email_template_form')

                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            </td>
        </tr>
    @endforeach
</table>

<div class="panel-list-pagination" id="pagination_email_templates_list">
    <?php echo $email_templates->links(); ?>
</div>
