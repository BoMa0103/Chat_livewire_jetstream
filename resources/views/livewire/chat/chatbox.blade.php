<div id="chat" class="chat">

    @if($selectedChat)
        @livewire('chat.chatbox-header', ['receiverInstance' => $receiverInstance], key('chatbox-header-' . $receiverInstance->id))
        @livewire('chat.chatbox-chat', ['messages' => $messages, 'selectedChat' => $selectedChat], key('chatbox-chat-' . $receiverInstance->id))
    @else
        @livewire('chat.no-chatbox')
    @endif

</div>

<script>

    $(document).on('click', '.return', function (){
        @this.dispatch('resetChat');
    });

    window.addEventListener('markMessageAsRead', event=>{
        let value = document.querySelectorAll('.status_tick');

        let valueArray = Array.from(value);

        console.log(valueArray);

        valueArray.forEach(element => {
            element.innerHTML = "<svg style=\"position: relative; top: 4px;\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 30\" width=\"24\" height=\"24\" fill=\"none\">" +
                "       <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M16.7071 8.20711C17.0976 7.81658 17.0976 7.18342 16.7071 6.79289C16.3166 6.40237 15.6834 6.40237 15.2929 6.79289L9.5 12.5858L8.70711 11.7929C8.31658 11.4024 7.68342 11.4024 7.29289 11.7929C6.90237 12.1834 6.90237 12.8166 7.29289 13.2071L8.08579 14L7 15.0858L3.70711 11.7929C3.31658 11.4024 2.68342 11.4024 2.29289 11.7929C1.90237 12.1834 1.90237 12.8166 2.29289 13.2071L6.29289 17.2071C6.68342 17.5976 7.31658 17.5976 7.70711 17.2071L9.5 15.4142L11.2929 17.2071C11.6834 17.5976 12.3166 17.5976 12.7071 17.2071L21.7071 8.20711C22.0976 7.81658 22.0976 7.18342 21.7071 6.79289C21.3166 6.40237 20.6834 6.40237 20.2929 6.79289L12 15.0858L10.9142 14L16.7071 8.20711Z\" fill=\"#4b5563\"/>" +
                "</svg>";

        });
    });

    window.addEventListener("DOMContentLoaded", (event) => {
        window.addEventListener('rowChatToBottom', event => {
            let messagesElement = document.getElementById('messages');

            if (messagesElement) {
                $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
            }
        });

        window.addEventListener('scrollEventHandle', event => {
            $('.chat-messages').scroll(function (){
                let top = $('.chat-messages').scrollTop();
                if(top == 0){
                    @this.dispatch('loadMore');
                }
            })
        });

        window.addEventListener('chatSelectedGetHeight', event => {
            let messagesElement = document.getElementById('messages');
            let height = messagesElement.scrollHeight;

            @this.dispatch('updateHeight', {
                height:height
            });
        });

        window.addEventListener('updatedHeight', event => {
            let messagesElement = document.getElementById('messages');
            let old = event.detail[0];
            let newHeight = messagesElement.scrollHeight;


            messagesElement.scrollTop = newHeight - old;

            @this.dispatch('updateHeight', {
                height:newHeight
            });
        });
    });

</script>




