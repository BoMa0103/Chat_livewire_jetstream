<div>
    <div class="overlay" id="overlay-chat-settings" onclick="showHideChatSettings()"></div>
    <div class="chat-settings" id="chat-settings">
        <ul>
            <li onclick="showHideLanguages()"><a>Languages</a></li>
            <li style="color: #ff4c4c" wire:click="deleteChat"><a>Delete chat</a></li>
        </ul>
    </div>
    <div class="chat-settings" id="chat-languages">
        <ul>
            @foreach($languages as $lang)
                <li wire:click='setLanguage("{{$lang}}")' {{$lang === $selectedLang ? 'class=selected' : ''}}>{{$lang}}</li>
            @endforeach
        </ul>
    </div>
</div>
