@extends('layouts.default')

@section('content')
    <div class="row page-title-bar">
        <div class="large-12 columns">
            <h1>Industry</h1>
        </div>
    </div>


    <p><a href="#" data-reveal-id="newIndustryForm" class="button tiny">Add New Industry</a></p>
    <div id="newIndustryForm" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">

        <h2>Add New Industry</h2>
        {{ Form::open(array('route' => 'industries.store','autocomplete' => 'off')) }}
        @include('industries._partials.form')
        {{ Form::close() }}
        <a class="close-reveal-modal" aria-label="Close">&#215;</a>

    </div>

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <?php echo $industries->links(); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <h5>Industries</h5>

                <table class="">
                    <thead>
                    <tr>
                        <th>Industry</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($industries as $industry)
                        <tr>
                            <td>{{ $industry->industry }}</td>
                            <td>
                                @if($industry->status == 1)
                                    Enabled {{ link_to_route('industries.disable', 'Disable', array($industry->id), array("class" => 'button alert tiny')) }}
                                @else
                                    Disabled {{ link_to_route('industries.enable', 'Enable', array($industry->id), array("class" => 'button success tiny')) }}
                                @endif
                            </td>

                            <td>
                                <a href="#" data-reveal-id="industry_edit_form_{{ $industry->id }}" class="button tiny">Edit ></a>
                                <div id="industry_edit_form_{{ $industry->id }}" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
                                    <h2>Edit Industry</h2>
                                    {{ Form::model($industry, array('route' => array('industries.update', $industry->id), 'method' => 'put', 'class' => 'form-horizontal')) }}
                                    @include('industries._partials.form')
                                    {{ Form::close() }}
                                    <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                                </div>
                            </td>
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
                <?php echo $industries->links(); ?>
            </div>
        </div>
    </div>

@stop