@extends('layouts.default')

@section('content')
    <div class="row page-title-bar">
        <div class="large-12 columns">
            <h1>Teams</h1>
        </div>
    </div>

    <p><a href="#" data-reveal-id="newTeamForm" class="button tiny">Add New Team</a></p>
    <div id="newTeamForm" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true"
         role="dialog">

        <h2>Add New Team</h2>
        {{ Form::open(array('route' => 'teams.store','autocomplete' => 'off')) }}
        @include('teams._partials.form')
        {{ Form::close() }}
        <a class="close-reveal-modal" aria-label="Close">&#215;</a>

    </div>

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <?php echo $teams->links(); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <h5>Teams</h5>

                <table class="">
                    <thead>
                    <tr>
                        <th>Team Name</th>
                        <th>Team Lead</th>
                        <th>Status</th>
                        <th></th>
                        <th>Employee Assignments</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($teams as $team)
                        <tr>
                            <td>{{ $team->team_name }}</td>
                            <td>
                                @if(is_object($team->teamlead))
                                    {{ $team->teamlead->given_name }}
                                @else
                                    NA
                                @endif
                            </td>
                            <td>
                                @if($team->status == 1)
                                    Enabled {{ link_to_route('teams.disable', 'Disable', array($team->id), array("class" => 'button alert tiny')) }}
                                @else
                                    Disabled {{ link_to_route('teams.enable', 'Enable', array($team->id), array("class" => 'button success tiny')) }}
                                @endif
                            </td>
                            <td>


                                <a href="#" data-reveal-id="team_edit_form_{{ $team->id }}" class="button tiny">Edit
                                    ></a>

                                <div id="team_edit_form_{{ $team->id }}" class="reveal-modal" data-reveal
                                     aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
                                    <h2>Edit Team</h2>
                                    {{ Form::model($team, array('route' => array('teams.update', $team->id), 'method' => 'put', 'class' => 'form-horizontal')) }}
                                    @include('teams._partials.form_edit')
                                    {{ Form::close() }}
                                    <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                                </div>

                            </td>
                            <td>
                                <a href="#" data-reveal-id="employee_assignment_form_{{ $team->id }}"
                                   class="button tiny">Edit ></a>

                                <div id="employee_assignment_form_{{ $team->id }}" class="reveal-modal" data-reveal
                                     aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
                                    <h2>Assign Employees</h2>

                                    @include('teams._partials.employee_assignment')

                                    <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                                </div>
                            </td>

                            <td>{{-- Form::open(array('route' => array('account_types.destroy', $accounttype->id), 'method' => 'delete')) }}
                    {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                    {{ Form::close() --}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <?php echo $teams->links(); ?>
            </div>
        </div>
    </div>

@stop