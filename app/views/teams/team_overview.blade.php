@extends('layouts.default')

@section('content')

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <h5>Teams</h5>

                <table class="">
                    <thead>
                    <tr>
                        <th>Team Name</th>
                        <th>Team Lead</th>
                        <th>Team Members</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($teams as $team)
                        <tr>
                            <td>{{ $team->team_name }}</td>
                            <td>{{ $team->teamlead->given_name }} {{ $team->teamlead->surname }}</td>
                            <td>
                                <ul style="font-size: 12px;">
                                    @foreach($employees_notlist as $employee)
                                        @if(Team::checkIfEmployeeAssigned($team->id,$employee->id))
                                            <li>{{ $employee->given_name }} {{ $employee->surname }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

@stop