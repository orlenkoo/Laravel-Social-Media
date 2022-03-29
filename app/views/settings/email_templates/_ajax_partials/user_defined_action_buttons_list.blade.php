<table>
    <tr>
        <th>Button Tag</th>
        <th>Button Name</th>
        <th>URL</th>
        <th>Value Text</th>
        <th>Style</th>
        <th>Status</th>
    </tr>
    @foreach($email_template_user_defined_action_buttons as $email_template_user_defined_action_button)
        <tr>
            <td>
                {{ $email_template_user_defined_action_button->button_tag }}
            </td>
            <td>
                <input id="email_template_user_defined_action_button_button_name_{{ $email_template_user_defined_action_button->id }}" type="text" value="{{ $email_template_user_defined_action_button->button_name }}" onchange="ajaxUpdateUserDefinedActionButtonName(this.value,'{{ $email_template_user_defined_action_button->id }}','{{ $email_template_user_defined_action_button->button_name }}')">
            </td>
            <td><input id="email_template_user_defined_action_button_url_{{ $email_template_user_defined_action_button->id }}" name="email_template_user_defined_action_button_url_{{ $email_template_user_defined_action_button->id }}" type="text" value="{{ $email_template_user_defined_action_button->url }}" onchange="ajaxUpdateIndividualFieldsOfModel('email_template_user_defined_action_buttons', '{{ $email_template_user_defined_action_button->id }}', 'url', this.value, 'email_template_user_defined_action_button_url_{{ $email_template_user_defined_action_button->id }}', 'EmailTemplateUserDefinedActionButton')"></td>
            <td><input id="email_template_user_defined_action_button_value_text_{{ $email_template_user_defined_action_button->id }}" name="email_template_user_defined_action_button_value_text_{{ $email_template_user_defined_action_button->id }}" type="text" value="{{ $email_template_user_defined_action_button->value_text }}" onchange="ajaxUpdateIndividualFieldsOfModel('email_template_user_defined_action_buttons', '{{ $email_template_user_defined_action_button->id }}', 'value_text', this.value, 'email_template_user_defined_action_button_value_text_{{ $email_template_user_defined_action_button->id }}', 'EmailTemplateUserDefinedActionButton')"></td>
            <td>
                <select name="email_template_user_defined_action_button_style_{{ $email_template_user_defined_action_button->id }}" id="email_template_user_defined_action_button_style_{{ $email_template_user_defined_action_button->id }}" class="campaign-media-channels-selectize" onchange="ajaxUpdateIndividualFieldsOfModel('email_template_user_defined_action_buttons', '{{ $email_template_user_defined_action_button->id }}', 'style', this.value, 'email_template_user_defined_action_button_style_{{ $email_template_user_defined_action_button->id }}', 'EmailTemplateUserDefinedActionButton')">
                    <?php
                    $styles = EmailTemplateUserDefinedActionButton::$styles;
                    ?>
                    <option value=''>Style</option>
                    @foreach($styles as $key => $value)
                        @if($key == $email_template_user_defined_action_button->style)
                            <option value='{{ $key }}' selected >{{ $value }}</option>
                        @else
                            <option value='{{ $key }}'>{{ $value }}</option>
                        @endif
                    @endforeach
                </select>
            </td>
            <td>
                <select name="email_template_user_defined_action_button_status_{{ $email_template_user_defined_action_button->id }}" id="email_template_user_defined_action_button_status_{{ $email_template_user_defined_action_button->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('email_template_user_defined_action_buttons', '{{ $email_template_user_defined_action_button->id }}', 'status', this.value, 'email_template_user_defined_action_button_status_{{ $email_template_user_defined_action_button->id }}', 'EmailTemplateUserDefinedActionButton')">
                    <option value="0" {{ $email_template_user_defined_action_button->status == 0? "selected": "" }}>Disabled</option>
                    <option value="1" {{ $email_template_user_defined_action_button->status == 1? "selected": "" }}>Enabled</option>
                </select>
            </td>
        </tr>
    @endforeach
</table>

<div class="panel-list-pagination" id="pagination_email_templates_action_buttons_list">
    <?php echo $email_template_user_defined_action_buttons->links(); ?>
</div>