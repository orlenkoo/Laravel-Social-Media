@extends('layouts.default')

@section('content')
    <div class="row page-title-bar">
        <div class="large-12 columns">
            <h1>Attachment Types</h1>
        </div>
    </div>


    <p><a href="#" data-reveal-id="newStageForm" class="button tiny">Add New Attachment Type</a></p>
    <div id="newStageForm" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">

        <h2>Add New Attachment Type</h2>
        {{ Form::open(array('route' => 'attachment_types.store','autocomplete' => 'off')) }}
        @include('attachment_types._partials.form')
        {{ Form::close() }}
        <a class="close-reveal-modal" aria-label="Close">&#215;</a>

    </div>

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <?php echo $attachmenttypes->links(); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <h5>Attachment Types</h5>

                <table class="">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Status</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($attachmenttypes as $attachmenttype)
                        <tr>
                            <td>{{ $attachmenttype->type }}</td>
                            <td>
                                @if($attachmenttype->status == 1)
                                    Enabled {{ link_to_route('attachment_types.disable', 'Disable', array($attachmenttype->id), array("class" => 'button alert tiny')) }}
                                @else
                                    Disabled {{ link_to_route('attachment_types.enable', 'Enable', array($attachmenttype->id), array("class" => 'button success tiny')) }}
                                @endif
                            </td>
                            <td>
                                <a href="#" data-reveal-id="attachment_types_edit_form_{{ $attachmenttype->id }}" class="button tiny">Edit ></a>
                                <div id="attachment_types_edit_form_{{ $attachmenttype->id }}" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
                                    <h2>Edit Attachment Type</h2>
                                    {{ Form::model($attachmenttype, array('route' => array('attachment_types.update', $attachmenttype->id), 'method' => 'put', 'class' => 'form-horizontal')) }}
                                    @include('attachment_types._partials.form_edit')
                                    {{ Form::close() }}
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
                <?php echo $attachmenttypes->links(); ?>
            </div>
        </div>
    </div>

@stop