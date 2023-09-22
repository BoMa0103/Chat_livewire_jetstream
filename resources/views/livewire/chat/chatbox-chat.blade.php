<div class="chat-messages" id="messages">
    @foreach($messages as $message)
        <div wire:key='{{$message->id}}' class="message {{auth()->id() == $message->user_id ? 'outgoing' : 'incoming'}}">
            <div class="message-content" id="message">
                {{$message->value}}
            </div>
            <div class="message-meta">
                <div class="message-time" id="time"> {{$message->created_at->format('H:i')}} </div>
                @php

                    if($message->user->id === auth()->id())

                        if($message->read_status == 0){

                            echo '<svg class="status_tick" style="position: relative; top: 5px; padding: 1px; margin-right: 4px;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 125">" +
                "<g transform="translate(0,-952.36218)"><path style="text-indent:0;text-transform:none;direction:ltr;block-progression:tb;baseline-shift:baseline;color:#4b5563;enable-background:accumulate;" d="m 88.98041,971.33516 a 6.0006,6.0006 0 0 0 -4.1562,1.7187 l -48.1876,46.34384 -21.875001,-17.6875 a 6.0102958,6.0102958 0 1 0 -7.5623997,9.3437 l 26.0000007,21 a 6.0006,6.0006 0 0 0 7.9374,-0.3437 l 51.999997,-50.00004 a 6.0006,6.0006 0 0 0 -4.156197,-10.375 z" fill="#4b5563" fill-opacity="1" stroke="none"  visibility="visible" display="inline" /></g>" +
                "</svg>';
                        } else {
                            echo '<svg style="position: relative; top: 4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 30" width="24" height="24" fill="none">" +
                "       <path fill-rule="evenodd" clip-rule="evenodd" d="M16.7071 8.20711C17.0976 7.81658 17.0976 7.18342 16.7071 6.79289C16.3166 6.40237 15.6834 6.40237 15.2929 6.79289L9.5 12.5858L8.70711 11.7929C8.31658 11.4024 7.68342 11.4024 7.29289 11.7929C6.90237 12.1834 6.90237 12.8166 7.29289 13.2071L8.08579 14L7 15.0858L3.70711 11.7929C3.31658 11.4024 2.68342 11.4024 2.29289 11.7929C1.90237 12.1834 1.90237 12.8166 2.29289 13.2071L6.29289 17.2071C6.68342 17.5976 7.31658 17.5976 7.70711 17.2071L9.5 15.4142L11.2929 17.2071C11.6834 17.5976 12.3166 17.5976 12.7071 17.2071L21.7071 8.20711C22.0976 7.81658 22.0976 7.18342 21.7071 6.79289C21.3166 6.40237 20.6834 6.40237 20.2929 6.79289L12 15.0858L10.9142 14L16.7071 8.20711Z" fill="#4b5563"/>" +
                "</svg>';
                        }

                @endphp
            </div>
        </div>
    @endforeach
</div>


