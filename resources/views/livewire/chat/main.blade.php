<style>
    @media (min-width: 1029px) {
        .chat-list {
            display: block;
        }

        .no-chat {
            display: flex;
        }

        .return {
            display: none;
        }

        .show-users-in-list {
            display: none;
        }
    }

    @media (max-width: 1028px) and (min-width: 768px) {
        .chat-list {
            display: block;
        }

        .no-chat {
            display: flex;
        }

        .users, .return {
            display: none;
        }

        .show-users-in-list {
            display: none;
        }
    }

    @media (max-width: 767px) {
        .chat-list, .users, .no-chat {
            display: none;
        }

        .return {
            display: block;
        }

        .concave-left {
            display: block !important;
        }

        .show-users-no-chat {
            display: none;
        }
    }
</style>

<div class="chat-container">
    <div class="chat-list">
        @livewire('chat.chat-list')
    </div>

    <div class="chat_container">
        @livewire('chat.chatbox')

        @livewire('chat.send-message')
    </div>

    <div class="users shadow">
        @livewire('chat.user-list')
    </div>

</div>



<script>
    window.onload = function () {
        const noChat = document.querySelector('.no-chat');

        if (noChat) {
            $('.chat-list').show();
            $('.chat-input').hide();

            if(window.innerWidth < 768) {
                $('.chat_container').hide();
            } else {
                $('.chat_container').show();
            }
        } else {
            $('.chat-input').show();

            if(window.innerWidth < 768) {
                $('.chat-list').hide();
            } else {
                $('.chat-list').show();
            }
        }
    };

    $(window).resize(function (){
        const noChat = document.querySelector('.no-chat');

        if (noChat) {
            $('.chat-list').show();
            $('.chat-input').hide();

            if(window.innerWidth < 768) {
                $('.chat_container').hide();
            } else {
                $('.chat_container').show();
            }
        } else {
            if(window.innerWidth < 768) {
                $('.chat-list').hide();
            } else {
                $('.chat-list').show();
            }
        }
    });

    $(document).on('click', '.chat-item', function (){
        $('.chat_container').show();

        if(window.innerWidth < 768) {
            $('.chat-list').hide();
        }
    });

    window.addEventListener('notify', event => {
        notifyMe(event.detail[0]);
    });

    window.addEventListener('markChatCircleAsOnline', event => {
        markChatAsOnline(event.detail[0]);
    });

    window.addEventListener('markChatCircleAsOffline', event => {
        markChatAsOffline(event.detail[0]);
    });

    $(document).on('click', '.return', function() {
        $('.no-chat').show();
        $('.chat-list').show();
        $('.chat').hide();
        $('.chat-input').hide();
        $('.chat_container').hide();
    });

    window.addEventListener('beforeunload', function (event) {
        @this.dispatch('sendEventMarkChatAsOffline');
    });
</script>


