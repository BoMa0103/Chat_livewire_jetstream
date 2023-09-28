<div>
    <div class="add-users-conv-list hidden" id="add-users-conv-list">
        <ul id="user-list">
            @if(!$users->count())
                No users
            @else
                @foreach($users as $user)
                    <li wire:click='addUserToConversation({{$user->id}})'>{{$user->name}}</li>
                @endforeach
            @endif
        </ul>
    </div>
    <div class="overlay" id="overlay-users" onclick="showHideUsers()"></div>
</div>
