@php use App\Models\Chat;use Illuminate\Support\Facades\DB; @endphp
<div class="wire-chat-list">

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
                    <a href="{{ route('profile.show') }}">
                        <li>Profile settings</li>
                    </a>
                    <li onclick="showInput()">Create Conversation</li>
                    <li onclick="selectTheme()">Select Theme</li>
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <a href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            <li style="color: #ff4c4c">Log out</li>
                        </a>
                    </form>
                </ul>
            </div>
            <div class="input-container" id="input-container">
                <input type="text" placeholder="Enter conversation name" id="conversation-name-input">
                <button class="send-button" onclick="createConversation()">Next</button>
            </div>
            <div class="select-themes-container" id="select-themes-container">
                <input type="radio" name="theme" id="theme1" value="theme1" onclick="selectedTheme('default')">
                <input type="radio" name="theme" id="theme2" value="theme2" onclick="selectedTheme('palegoldenrod')">
                <input type="radio" name="theme" id="theme3" value="theme3" onclick="selectedTheme('pink')">
                <input type="radio" name="theme" id="theme4" value="theme4" onclick="selectedTheme('lightblue')">
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
                       class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 search-input"
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
                                <p class="chat-last-message" id="chat-last-message">
                                    {!! $this->customHtmlspecialcharsForImg($chat->messages->last()) !== null ? $this->customHtmlspecialcharsForImg($chat->messages->last()) : '' !!}
                                </p>
                            </div>
                            <p class="chat-last-message-data"
                               id="chat-last-message-data"> {{$chat->messages->last() ? $chat->messages->last()->created_at->format('H:i') : ''}} </p>
                            @php

                                if ($chat->chat_type == Chat::PRIVATE) {
                                    $unreadMessagesCount = count($chat->messages->where('read_status', 0)->where('user_id', '!=', auth()->user()->id));
                                } else {
                                    $unreadMessagesCount = DB::table('message_user')
                                                        ->where('chat_id', $chat->id)
                                                        ->where('user_id', $auth_id)
                                                        ->where('read_status', 0)
                                                        ->count();
                                }

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
        let dropdownButton = document.querySelector('.dropdown-toggle');
        let dropdownMenu = document.querySelector('.dropdown-menu');
        let overlay = document.querySelector('.overlay');

        dropdownButton.addEventListener('click', function () {
            let overlay = document.getElementById('overlay-menu');
            let menu = document.getElementById('menu');

            if (overlay.style.display === 'block' && menu.style.display === 'block') {
                overlay.style.display = 'none';
                menu.style.display = 'none';
            } else {
                overlay.style.display = 'block';
                menu.style.display = 'block';
            }
        });

        function showInput() {
            let inputContainer = document.getElementById('input-container');
            let menu = document.getElementById('menu');

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

        function selectTheme() {
            let selectThemesContainer = document.getElementById('select-themes-container');
            let menu = document.getElementById('menu');

            let color = document.documentElement.style.getPropertyValue('--back-for-chat-color');

            switch (color) {
                case '#2d3748':
                    $('#theme1').prop('checked', true);
                    break;
                case '#AEC09A':
                    $('#theme2').prop('checked', true);
                    break;
                case '#85b3b1':
                    $('#theme3').prop('checked', true);
                    break;
                case '#e6ad56':
                    $('#theme4').prop('checked', true);
                    break;
            }

            menu.style.display = 'none';
            selectThemesContainer.style.display = 'flex';
        }

        function selectedTheme(index) {
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

            switch (index) {
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

        @this.dispatch('changeTheme', {
            theme: index
        })
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
