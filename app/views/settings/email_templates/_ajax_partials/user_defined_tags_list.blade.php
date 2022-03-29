<table>
    <tr>
        <th>Tag</th>
        <th>Name</th>
        <th>Value</th>
        <th>Status</th>
    </tr>
    @foreach($email_template_user_defined_tags as $email_template_user_defined_tag)
        <tr>
            <td>{{ $email_template_user_defined_tag->tag }}</td>
            <td>
                <input id="email_template_user_defined_name_{{ $email_template_user_defined_tag->id }}" type="text" value="{{ $email_template_user_defined_tag->name }}" onchange="ajaxUpdateUserDefinedTagName(this.value,'{{ $email_template_user_defined_tag->id }}','{{ $email_template_user_defined_tag->name }}')">
            </td>
            <td><input id="email_template_user_defined_value_{{ $email_template_user_defined_tag->id }}" name="email_template_user_defined_value_{{ $email_template_user_defined_tag->id }}" type="text" value="{{ $email_template_user_defined_tag->value }}" onchange="ajaxUpdateIndividualFieldsOfModel('email_template_user_defined_tags', '{{ $email_template_user_defined_tag->id }}', 'value', this.value, 'email_template_user_defined_value_{{ $email_template_user_defined_tag->id }}', 'EmailTemplateUserDefinedTag')"></td>
            <td>
                <select name="email_template_user_defined_status_{{ $email_template_user_defined_tag->id }}" id="email_template_user_defined_status_{{ $email_template_user_defined_tag->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('email_template_user_defined_tags', '{{ $email_template_user_defined_tag->id }}', 'status', this.value, 'email_template_user_defined_status_{{ $email_template_user_defined_tag->id }}', 'EmailTemplateUserDefinedTag')">
                    <option value="0" {{ $email_template_user_defined_tag->status == 0? "selected": "" }}>Disabled</option>
                    <option value="1" {{ $email_template_user_defined_tag->status == 1? "selected": "" }}>Enabled</option>
                </select>
            </td>
        </tr>
    @endforeach
</table>

<div class="panel-list-pagination" id="pagination_email_templates_tags_list">
    <?php echo $email_template_user_defined_tags->links(); ?>
</div>