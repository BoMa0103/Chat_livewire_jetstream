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

        .img-button, .emoji-button {
            display: none;
        }
    }

    @media (max-width: 767px) {
        .chat-list, .users, .no-chat {
            border: none;
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

        .img-button, .emoji-button {
            display: none;
        }
    }
</style>

<div class="chat-container">
    <div class="chat-list">
        @livewire('chat.chat-list.chat-list')
    </div>

    <div class="chatbox">
        @livewire('chat.chat-box.chatbox')
    </div>

    <div class="users shadow">
        @livewire('chat.user-list.user-list')
    </div>

</div>

<script>
    window.stylesConfig = @json(config('styles'));

    window.onload = function () {
        const noChat = document.querySelector('.no-chat');

        if (noChat) {
            $('.chat-list').show();
            $('.chat-input').hide();

            if(window.innerWidth < 768) {
                $('.chatbox').hide();
            } else {
                $('.chatbox').show();
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
                $('.chatbox').hide();
            } else {
                $('.chatbox').show();
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
        $('.chatbox').show();

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

    window.addEventListener('hideMessageInput', event => {
        $('.chat-input').hide();
    });

    window.addEventListener('setTheme', event => {
        setTheme(event.detail[0]);
    });

    $(document).on('click', '.return', function() {
        $('.no-chat').show();
        $('.chat-list').show();
        $('.chat').hide();
        $('.chat-input').hide();
        $('.chatbox').hide();
    });

    window.myLivewireHandler = function () {
        @this.dispatch('sendMessage');
    };
</script>


