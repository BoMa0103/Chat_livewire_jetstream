@php use App\Models\Chat; @endphp
<div class="chat-header">
    <div class="return">
        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor"
             class="bi bi-arrow-left-short" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                  d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
        </svg>
    </div>
    <div class="user-info">
        <div class="chat-header-name">{{$chatName}}</div>
        @if ($selectedChat->chat_type !== Chat::CONVERSATION)
            <div class="last-seen">({{$lastSeen ? $lastSeen : 'last seen a long time ago'}})</div>
        @endif
    </div>
    <div class="concave-left" id="concave-left">
        <div class='leftconcave'></div>
    </div>
    <div class="concave-right" id="concave-right">
        <div class='rightconcave'></div>
    </div>
    <div class="chat-header-nav">
        <div class="chat-settings-button" onclick="openChatSettings()">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
            </svg>
        </div>
        @if ($selectedChat->chat_type !== Chat::PRIVATE)
            <div class="add-users-conv"
                 onclick="showHideUsers()">
                <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor" class="bi bi-plus"
                     viewBox="0 0 16 16">
                    <path
                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
        @endif
        <div class="show-users" onclick="showUsers()">
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor"
                 class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
            </svg>
        </div>
    </div>
</div>

