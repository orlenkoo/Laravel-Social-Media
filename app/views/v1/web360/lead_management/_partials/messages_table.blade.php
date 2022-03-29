<div class="row expanded">
    <div class="large-12 columns">
        @foreach($messages as $message)
            <div class="row messenger-thread-message-container">
                <div class="large-6 columns">
                    @if($message->sent_by == 'Event Planner')
                        <div class="message-left">
                            <p><strong>Customer: </strong>{{ $message->message }}</p>
                            <em>{{ $message->timestamp }}</em>
                        </div>
                    @endif
                </div>
                <div class="large-6 columns">
                    @if($message->sent_by == 'Vendor')
                        <div class="message-right">
                            <p>
                                <strong>
                                    @if(is_object($message->employee))
                                        ({{ $message->employee->getEmployeeFullName() }}) :
                                    @endif
                                </strong>
                                {{ $message->message }}
                            </p>
                            <em>{{ $message->timestamp }}</em>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

