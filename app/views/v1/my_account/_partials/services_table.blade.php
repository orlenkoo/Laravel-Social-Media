<div class="table-scroll">

    <table id="event360_vendor_services_details" class="">
        <thead>
        <tr>
            <th>Service</th>
            <th>Description</th>
            <th></th>
            <th>ID</th>
        </tr>
        </thead>
        <tbody>
        @foreach($services as $service)
            <tr>
                <td>{{ $service->service }}</td>
                <td>{{ $service->description }}</td>
                <td>
                    <a href="#" data-open="editServiceForm_{{ $service->id }}"
                       class="button tiny">Edit</a>

                    <div id="editServiceForm_{{ $service->id }}" class="reveal" data-reveal>

                        <h2>Edit Service</h2>
                        {{ Form::model($service, array('route' => array('event360.vendor_profile.services.update', $service->id), 'method' => 'put', 'class' => 'form-horizontal')) }}
                        @include('v1.my_account._partials.services_form_edit')
                        {{ Form::close() }}

                        @include('v1.my_account.services.images_index')

                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>



                    </div>
                </td>
                <td>{{ $service->id }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

