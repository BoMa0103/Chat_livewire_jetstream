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
    }
</style>

<div class="chat-container">
    <div class="chat-list">
        @livewire('chat.chat-list')
    </div>

    <div class="chat_container">
        @livewire('chat.chatbox')
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

    window.addEventListener('hideMessageInput', event => {
        $('.chat-input').hide();
    });

    window.addEventListener('setTheme', event => {
        let backForChatColor;
        let backForChatInfoColor;
        let mainTextColor;
        let secondTextColor;
        let backMessageColor;
        let backSearchSendFormColor;
        let backTextAreaColor;
        let hoverColor;
        let placeholderColor;
        let placeholderSearchColor;

        switch (event.detail[0]) {
            case 'default':
                backForChatColor = '#2d3748';
                backForChatInfoColor = '#1d232a';
                mainTextColor = '#e5dfd3'
                backMessageColor = '#1f2227';
                backSearchSendFormColor = '#374151';
                backTextAreaColor = '#1F2937';
                secondTextColor = '#4b5563';
                hoverColor = '#6b7280';
                placeholderColor = '#4b5563';
                placeholderSearchColor = '#9CA3AF';
                break;
            case 'palegoldenrod':
                backForChatColor = '#AEC09A';
                backForChatInfoColor = '#536d6c';
                mainTextColor = '#e5dfd3';
                backMessageColor = '#314448';
                backSearchSendFormColor = '#7c9a92';
                backTextAreaColor = '#96aea7';
                secondTextColor = '#7c9a92';
                hoverColor = '#89a49c';
                placeholderColor = '#314448';
                placeholderSearchColor = '#b5c6c1';
                break;
            case 'pink':
                backForChatColor = '#85b3b1';
                backForChatInfoColor = '#535d69';
                mainTextColor = '#e5dfd3'
                backMessageColor = '#535d74';
                backSearchSendFormColor = '#596C7B';
                backTextAreaColor = '#7A8995';
                secondTextColor = '#7A8995';
                hoverColor = '#87949f';
                placeholderColor = '#596C7B';
                placeholderSearchColor = '#a1acb4';
                break;
            case 'lightblue':
                backForChatColor = '#e6ad56';
                backForChatInfoColor = '#68513b';
                mainTextColor = '#e0d7d0'
                backMessageColor = '#A78B71';
                backSearchSendFormColor = '#A78B71';
                backTextAreaColor = '#c1ad9b';
                secondTextColor = '#856f5a';
                hoverColor = '#9D8B7B';
                placeholderColor = '#A78B71';
                placeholderSearchColor = '#cab9a9';
                break;
        }

        document.documentElement.style.setProperty('--back-for-chat-color', backForChatColor);
        document.documentElement.style.setProperty('--back-for-chat-info-color', backForChatInfoColor);
        document.documentElement.style.setProperty('--main-text-color', mainTextColor);
        document.documentElement.style.setProperty('--second-text-color', secondTextColor);
        document.documentElement.style.setProperty('--back-message-color', backMessageColor);
        document.documentElement.style.setProperty('--back-search-send-form-color', backSearchSendFormColor);
        document.documentElement.style.setProperty('--back-text-area-color', backTextAreaColor);
        document.documentElement.style.setProperty('--hover-color', hoverColor);
        document.documentElement.style.setProperty('--placeholder-color', placeholderColor);
        document.documentElement.style.setProperty('--placeholder-search-color', placeholderSearchColor);
    });

    $(document).on('click', '.return', function() {
        $('.no-chat').show();
        $('.chat-list').show();
        $('.chat').hide();
        $('.chat-input').hide();
        $('.chat_container').hide();
    });

    // @todo need to rebuild using pusher events
    window.addEventListener('beforeunload', function (event) {
        @this.dispatch('sendEventMarkChatAsOffline');
    });

    window.myLivewireHandler = function () {
        @this.dispatch('sendMessage');
    };
</script>


