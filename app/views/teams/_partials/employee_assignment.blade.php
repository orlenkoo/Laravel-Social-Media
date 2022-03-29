<div class="row">
    <div class="large-12 columns">
        <div class="panel">
            <h5>Assign Employees</h5>
            <script type="text/javascript" language="javascript">// <![CDATA[
                function checkAll(formname, checktoggle)
                {
                    var checkboxes = new Array();
                    checkboxes = document[formname].getElementsByTagName('input');

                    for (var i=0; i<checkboxes.length; i++)  {
                        if (checkboxes[i].type == 'checkbox')   {
                            checkboxes[i].checked = checktoggle;
                        }
                    }
                }
                // ]]></script>
            <a onclick="javascript:checkAll('widgets_form_{{ $team->id }}', true);" class="button tiny" >Check All</a>
            <a onclick="javascript:checkAll('widgets_form_{{ $team->id }}', false);" class="button tiny" >Uncheck All</a>



            {{ Form::model($team->teamassignments, array('route' => array('team.employee.assignment', $team->id), 'method' => 'post', 'class' => 'form-horizontal', 'name' => 'widgets_form_'.$team->id)) }}

            {{ Form::hidden('team_id', $team->id) }}
            {{ Form::hidden('organization_id', Session::get('user-organization-id')) }}
            @foreach($employees_notlist as $employee)
                <div class="row">
                    <div class="large-12 columns">
                        @if(Team::checkIfEmployeeAssigned($team->id,$employee->id))
                            <input type="checkbox" name="employees[]" id="employees[]" value="{{ $employee->id }}" checked/>{{ $employee->given_name }} {{ $employee->surname }}
                        @else
                            <input type="checkbox" name="employees[]" id="employees[]" value="{{ $employee->id }}"/>{{ $employee->given_name }} {{ $employee->surname }}
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="row">
                <div class="large-12 columns">
                    {{ Form::submit('Save', array("class" => "button success tiny")) }}

                </div>
            </div>

            {{ Form::close() }}
        </div>
    </div>
</div>