@php use App\Models\Chat; @endphp
<div>

    <div class="flex-container">
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-list"
                     viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                          d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </button>
            <div class="overlay" id="overlay-menu" onclick="showHideMenu()"></div>
            <div class="menu" id="menu">
                <ul>
                    <li><a onclick="showInput()">Create conversation</a></li>
                </ul>
            </div>
            <div class="input-container" id="input-container">
                <input type="text" placeholder="Enter conversation name" id="conversation-name-input">
                <button class="send-button" onclick="createConversation()">Next</button>
            </div>
        </div>
        <div class="chat-search">
            <label for="default-search"
                   class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search" id="default-search"
                       class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="Search" required>
            </div>
        </div>

        <div class="show-users-in-list" onclick="showUsers()">
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor"
                 class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
            </svg>
        </div>
    </div>

    <div class="chat-list-box">

        <ul id="chat-list">
            @foreach($chats as $key => $chat)

                <li class="chat-item @php
                    if($selectedChat) {
                        if($chat->id === $selectedChat->id)
                        {
                            echo ' selected';
                            if($key == 0){
                                $selectedFirstChatFlag = true;
                                echo ' firstChat';
                            } else{$selectedFirstChatFlag = false;
                            echo ' nChat';}
                        }
                    } @endphp" wire:key='{{$chat->id}}'
                    wire:click="chatUserSelected({{$chat}})">
                    <div>
                        <div class='chat-info' id="{{$chat->id}}">
                            <img class="w-7 h-7 mr-6 rounded-full avatar"
                                 src="/images/alexander-hipp-iEEBWgY_6lA-unsplash.jpg"
                                 alt="User image">
                            <div class='online-circle' wire:ignore></div>
                            <div class='chat-name-last-message'>
                                <p class="chat-name">
                                    @php if($chat->chat_type === Chat::PRIVATE) {
                                        echo $this->getChatUserInstance($chat, $name='name');
                                    } else if($chat->chat_type === Chat::CONVERSATION){
                                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-people-fill conv-icon" viewBox="0 0 16 16">
  <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216ZM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
</svg>';
                                        echo $chat->name;
                                    } @endphp
                                </p>
                                <p class="chat-last-message"
                                   id="chat-last-message"> {!! $chat->messages->last() ? $chat->messages->last()->content : '' !!} </p>
                            </div>
                            <p class="chat-last-message-data"
                               id="chat-last-message-data"> {{$chat->messages->last() ? $chat->messages->last()->created_at->format('H:i') : ''}} </p>
                            @php
                                $unreadMessagesCount = count($chat->messages->where('read_status', 0)->where('user_id', '!=', auth()->user()->id));
                                if($unreadMessagesCount) {
                                    echo '<div class="chat-unread-messages-count">' .  $unreadMessagesCount . '</div>';
                                }
                            @endphp

                        </div>
                    </div>
                </li>

            @endforeach

            @php
                if($selectedFirstChatFlag) {
                    echo '<style> .concave-left{display:none;} .messages{border-top-left-radius: 0;}</style>';
                }else{
                    echo '<style> .concave-left{display:flex;}</style>';
                }
            @endphp

        </ul>

    </div>

    <script>
        var dropdownButton = document.querySelector('.dropdown-toggle');
        var dropdownMenu = document.querySelector('.dropdown-menu');
        var overlay = document.querySelector('.overlay');

        dropdownButton.addEventListener('click', function () {
            var overlay = document.getElementById('overlay-menu');
            var menu = document.getElementById('menu');

            if (overlay.style.display === 'block' && menu.style.display === 'block') {
                overlay.style.display = 'none';
                menu.style.display = 'none';
            } else {
                overlay.style.display = 'block';
                menu.style.display = 'block';
            }
        });

        function showInput() {
            var inputContainer = document.getElementById('input-container');
            var menu = document.getElementById('menu');

            menu.style.display = 'none';
            inputContainer.style.display = 'flex';
        }

        function createConversation() {
            let conversationName = document.getElementById('conversation-name-input');

            if (conversationName.value.trim() !== '') {
            @this.dispatch('createConversation', {
                conversationName: conversationName.value.trim()
            })

            }
        }

        let search = document.getElementById('default-search');

        search.addEventListener('input', function () {
            if (search.value.trim() !== '') {
            @this.dispatch('searchChats', {
                chatName: search.value.trim()
            })

            } else {
            @this.dispatch('refreshChatList')

            }
        });
    </script>

</div>
