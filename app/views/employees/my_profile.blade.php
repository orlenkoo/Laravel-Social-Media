@extends('layouts.default')
@section('content')

    <div class="row expanded">
        <div class="large-2 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h5>Profile Settings</h5>
                </div>
                <div class="panel-content">
                    <ul class="vertical tabs" data-tabs id="example-tabs">
                        <li class="tabs-title is-active"><a data-tabs-target="panel_my_profile" href="#panel_my_profile" aria-selected="true">My Profile</a></li>
                        <li class="tabs-title"><a data-tabs-target="panel_email_signature" href="#panel_email_signature">Email Signature</a></li>
                        <li class="tabs-title"><a data-tabs-target="panel_notifications" href="#panel_notifications">Notifications</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="large-10 columns">
            <div class="panel">
                <div class="row expanded collapse">
                    <div data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel_my_profile">
                            {{ Form::open(array('route' => 'employees.ajax.save_profile_changes', 'ajax' => 'true', 'id' => 'my_profile_form','autocomplete' => 'off')) }}

                            <div class="panel-heading">
                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <h1>My Profile</h1>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-content">
                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <label for="employee_form_given_name">Given Name
                                            <input type="text" id="employee_form_given_name" name="given_name" value="{{ $employee->given_name }}" placeholder="Given Name">
                                        </label>
                                    </div>
                                </div>

                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <label for="employee_form_surname">Surname
                                            <input type="text" id="employee_form_surname" name="surname" value="{{ $employee->surname }}" placeholder="Surname">
                                        </label>
                                    </div>
                                </div>

                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <label for="employee_form_designation">Designation
                                            <input type="text" id="employee_form_designation" name="designation" value="{{ $employee->designation }}" placeholder="Designation">
                                        </label>
                                    </div>
                                </div>

                                <div class="row expanded">
                                    <div class="large-4 columns">
                                        <label for="employee_form_country">Country Code
                                            <select id="employee_form_country_code" name="country_code" class="form-control">
                                                <?php
                                                $country_codes = Employee::$country_codes;
                                                ?>
                                                @foreach($country_codes as $key => $value)
                                                    @if(isset($employee->country_code))
                                                        <option value="{{ $key }}" {{ $key == $employee->country_code ? "selected" : '' }}>{{ $value }}</option>
                                                    @else
                                                        <option value="{{ $key }}" {{ $key == "+65" ? "selected" : '' }}>{{ $value }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                    <div class="large-8 columns">
                                        <label for="employee_form_contact_no">Phone No
                                            <input type="text" id="employee_form_contact_no" name="contact_no" value="{{ $employee->contact_no }}" placeholder="Contact No" class="numbersonly" data-validation="required">
                                        </label>
                                    </div>
                                </div>


                            </div>
                            <div class="panel-footer">
                                <div class="row expanded save_bar">
                                    <div class="large-12 columns text-right">
                                        {{ Form::submit('Save Changes', array("class" => "button tiny success", "id" => "employee_form_save_profile_changes_button")) }}
                                    </div>
                                </div>
                                <div class="row expanded loading_animation" style="display: none;">
                                    <div class="large-12 columns text-center">
                                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>

                       

                        <div class="tabs-panel" id="panel_email_signature">

                            <div class="panel-heading">
                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <h1>Email Signature</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-content">
                                {{ Form::open(array('route' => 'employees.ajax.email.signature.change', 'ajax' => 'true', 'id' => 'my_profile_new_email_signature','autocomplete' => 'off')) }}
                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <label for="employee_form_signature_html">Signature HTML</label>
                                        @if(isset($employee) && is_object($employee))
                                            <div class="controls">{{ Form::textarea('signature_html','', array('id' => 'signature_html','data-validation'=>'')) }}</div>
                                            <script>

                                                $( document ).ready(function() {
                                                    var html_signature = '<?php echo str_replace(array("\r\n", "\n", "\r"), ' ', $employee->signature_html); ?>';
                                                    setupCKEditor('signature_html',html_signature,'Basic');
                                                });

                                            </script>
                                        @else
                                            <div class="controls">{{ Form::textarea('signature_html','', array('id' => 'signature_html','data-validation'=>'')) }}</div>
                                            <script>

                                                $( document ).ready(function() {
                                                    setupCKEditor('signature_html','','Basic');
                                                });

                                            </script>
                                        @endif
                                    </div>
                                </div>

                                <br>

                                <div class="row expanded save_bar text-right">
                                    <div class="large-12 columns">
                                        {{ Form::submit('Save Email Signature', array("class" => "button tiny success", "id" => "employee_form_save_email_signature_button")) }}
                                    </div>
                                </div>

                                {{ Form::close() }}


                               


                                <div class="row expanded save_bar">
                                    <div class="large-12 columns text-right">
                                        {{ Form::submit('Save Email Signature Image', array("class" => "button tiny success", "id" => "employee_form_save_email_signature_image_button")) }}
                                    </div>
                                </div>
                                <div class="row expanded loading_animation" style="display: none;">
                                    <div class="large-12 columns text-center">
                                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>

                        
                        <div class="tabs-panel" id="panel_notifications">
                            <div class="panel-heading">
                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <h1>Notifications</h1>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-content">
                                <table >
                                    <tr>
                                        <th>
                                            Type
                                        </th>
                                        <th>
                                            SMS (Available Soon)
                                        </th>
                                        <th>
                                            Email
                                        </th>
                                        <th>
                                            App Notification (Available Soon)
                                        </th>
                                    </tr>
                                    <?php
                                        if(Session::get('user-level') == 'marketing') {
                                            $types_of_alerts = Notification::$marketing_type_of_alerts;
                                        } else {
                                            $types_of_alerts = Notification::$types_of_alerts;
                                        }
                                    ?>
                                    @foreach($types_of_alerts as $key => $value)
                                        <tr>
                                            <td>
                                                {{ $value }}
                                            </td>
                                            <td>
                                                <input type="checkbox" disabled="disabled">
                                                {{--<input type="checkbox" onchange="ajaxUpdateEmployeeNotifications('{{ $key }}', 'sms', this.checked)" {{ $employee->getEmployeeNotificationStatus($key, 'sms') == 1? 'checked': '' }}>--}}
                                            </td>
                                            <td>
                                                <input type="checkbox" onchange="ajaxUpdateEmployeeNotifications('{{ $key }}', 'email', this.checked)" {{ $employee->getEmployeeNotificationStatus($key, 'email') == 1? 'checked': '' }}>
                                            </td>
                                            <td>
                                                <input type="checkbox" disabled="disabled">
                                                {{--<input type="checkbox" onchange="ajaxUpdateEmployeeNotifications('{{ $key }}', 'mobile_app_notification', this.checked)" {{ $employee->getEmployeeNotificationStatus($key, 'mobile_app_notification') == 1? 'checked': '' }}>--}}
                                            </td>
                                        </tr>
                                    @endforeach    
                                </table>
                                <script>
                                    function ajaxUpdateEmployeeNotifications(type_of_alert, type_of_notification, value) {
                                        if(value) {
                                            value = 1;
                                        } else {
                                            value = 0;
                                        }

                                        var employee_id = '{{ Session::get('user-id') }}';

                                        $.ajax({
                                            url: '{{ URL::route('employees.ajax_update_employee_notifications') }}',
                                            method: 'POST',
                                            data: {
                                                employee_id: employee_id,
                                                type_of_alert: type_of_alert,
                                                type_of_notification: type_of_notification,
                                                value: value
                                            },
                                            success:function(response)
                                            {
                                                $.notify(response, "success");
                                            }
                                        });
                                    }
                                </script>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

    </div>









    <script>
        function getFile(filePath) {
            return filePath.substr(filePath.lastIndexOf('\\') + 1).split('.')[0];
        }


        

        $(document).ready(function() {
            setUpAjaxForm('my_profile_form', 'create', '','');
            setUpAjaxForm('my_profile_new_password', 'create','', function(){
                $('#employee_form_password').val('');
            });
            
        });

        

        


        
       
        





    </script>

@stop