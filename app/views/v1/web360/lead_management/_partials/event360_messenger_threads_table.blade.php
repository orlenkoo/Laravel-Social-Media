<div class="table-scroll">

    <table id="event360_messenger_thread_details" class="">
        <thead>
        <tr>
            <th>Date and Time</th>
            <th>Customer Name</th>
            <th>Subject</th>
            <th></th>
            <th>ID</th>
        </tr>
        </thead>
        <tbody>
        @foreach($event360_messenger_threads as $event360_messenger_thread)
            @if(!Event360MessengerThread::checkEvent360MessengerLastMessageSentByVendor($event360_messenger_thread->id))
                <tr>
                    <td>{{ $event360_messenger_thread->timestamp }}</td>
                    <td>
                        @if(is_object($event360_messenger_thread->event360EventPlannerProfile))
                            {{ $event360_messenger_thread->event360EventPlannerProfile->getEventPlannerName() }}
                        @else
                            NA
                        @endif

                    </td>
                    <td>{{ $event360_messenger_thread->subject }}</td>
                    <td>
                        <a onclick="loadEvent360MessengerThreadMessages('{{$event360_messenger_thread->id}}');" class=" <?php echo Event360MessengerThread::checkEvent360MessengerLastMessageSentByVendor($event360_messenger_thread->id) ? 'button tiny' : 'button tiny alert' ?>" >Messages ({{ count($event360_messenger_thread->event360MessengerThreadMessages) }})</a>
                    </td>
                    <td>
                        {{ $event360_messenger_thread->id }}
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>

