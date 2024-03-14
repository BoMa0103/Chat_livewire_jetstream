function markChatAsOnline(chat_id) {
    let chat = document.getElementById(chat_id);

    if (!chat) {
        return;
    }

    let circleDiv = chat.getElementsByClassName('online-circle')[0];

    circleDiv.style.display = 'block';
}

function markChatAsOffline(chat_id) {
    let chat = document.getElementById(chat_id);
    let circleDiv = chat.getElementsByClassName('online-circle')[0];

    circleDiv.style.display = 'none';
}


function markSelectedChat(li) {
    li.classList.add('selected');

    if (li.classList.contains('firstChat')) {
        document.getElementById('concave-left').style.display = 'none';
        document.getElementById('messages').style.borderTopLeftRadius = '0';
    } else {
        document.getElementById('concave-left').style.display = 'flex';
    }
}

function showUsers() {
    let users = $('.users');
    let users_header = $('.users_header');
    users.show()
    users.css('position', 'absolute');
    users.css('right', '0');
    users.css('width', '200px');

    users_header.css('padding-right', '38px');
}

function showHideMenu() {
    let overlay = document.getElementById('overlay-menu');
    let inputContainer = document.getElementById('input-container');
    let menu = document.getElementById('menu');
    let selectThemesContainer = document.getElementById('select-themes-container');

    if (overlay.style.display === 'block' && menu.style.display === 'block') {
        overlay.style.display = 'none';
        menu.style.display = 'none';
    } else if (overlay.style.display === 'block' && inputContainer.style.display === 'flex') {
        overlay.style.display = 'none';
        inputContainer.style.display = 'none';
    } else if(overlay.style.display === 'block' && selectThemesContainer.style.display === 'flex') {
        overlay.style.display = 'none';
        selectThemesContainer.style.display = 'none';
    } else {
        overlay.style.display = 'block';
        menu.style.display = 'block';
    }
}

function showHideUsers() {
    let overlay = document.getElementById('overlay-users');
    let users = document.getElementById('add-users-conv-list');

    if (overlay.style.display === 'block' && users.style.display === 'block') {
        overlay.style.display = 'none';
        users.style.display = 'none';
    } else {
        overlay.style.display = 'block';
        users.style.display = 'block';
    }
}

function hideUsers() {
    let users = $('.users');
    let users_header = $('.users_header');
    users.hide()
    users.css('position', '');
    users.css('right', '');
    users.css('width', '');

    users_header.css('padding-right', '');
}

function openChatSettings() {
    let overlay = document.getElementById('overlay-chat-settings');
    let chatSettings = document.getElementById('chat-settings');

    if (overlay.style.display === 'block' && chatSettings.style.display === 'block') {
        overlay.style.display = 'none';
        chatSettings.style.display = 'none';
    } else {
        overlay.style.display = 'block';
        chatSettings.style.display = 'block';
    }
}

function showHideChatSettings() {
    let overlay = document.getElementById('overlay-chat-settings');
    let chatSettings = document.getElementById('chat-settings');

    if (overlay.style.display === 'block' && chatSettings.style.display === 'block') {
        overlay.style.display = 'none';
        chatSettings.style.display = 'none';
    } else {
        overlay.style.display = 'block';
        chatSettings.style.display = 'block';
    }
}

function showHideLanguages() {
    let chatLanguages = document.getElementById('chat-languages');
    let chatSettings = document.getElementById('chat-settings');
    chatSettings.style.display = 'none';

    if (chatLanguages.style.display === 'block') {
        chatLanguages.style.display = 'none';
    } else {
        chatLanguages.style.display = 'block';
    }
}

/* Notifications */

function notifyMe(json) {
    if (!("Notification" in window)) {
        alert("This browser does not support desktop notification");
    } else if (Notification.permission === "granted") {
        const notification = new Notification("Chat", {
            body: 'New message from ' + json.user.name,
            icon: '/images/free-icon-chat-bubble-6068634.png',
        });

        document.querySelector('link[rel="icon"]').href = '/images/free-icon-chat-bubble-6068634-new-messages.png';

        const audio = new Audio('/audio/mixkit-message-pop-alert-2354.mp3');
        audio.play();

    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then((permission) => {
            if (permission === "granted") {
                const notification = new Notification("Chat", {
                    body: 'New message from ' + json.user.name,
                    icon: '/images/free-icon-chat-bubble-6068634.png',
                });

                document.querySelector('link[rel="icon"]').href = '/images/free-icon-chat-bubble-6068634-new-messages.png';

                const audio = new Audio('/audio/mixkit-message-pop-alert-2354.mp3');
                audio.play();
            }
        });
    }
}

document.addEventListener('send-message-loaded', function () {
    let textArea = document.getElementById('text');
    let sendButton = document.getElementById('send');

    textArea.addEventListener('input', function () {
        if (textArea.value.trim() !== '') {
            sendButton.removeAttribute('disabled');
        } else {
            sendButton.setAttribute('disabled', 'disabled');
        }
    });

    $(".emoji-dropdown .emoji-button").click(function () {
        var $menu = $(this).siblings(".emoji-menu");
        if ($menu.css("display") === "none") {
            $menu.css("display", "flex");
        } else {
            $menu.css("display", "none");
        }
    });

    function insertEmoji(emoji) {
        var textarea = document.getElementById('text');
        textarea.value += emoji;
        textarea.dispatchEvent(new Event('input'));
    }

    $(".emoji-item").click(function () {
        var selectedEmoji = $(this).text();
        insertEmoji(selectedEmoji);
    });

    $(document).click(function (e) {
        if (!$(e.target).closest(".emoji-dropdown").length) {
            $(".emoji-menu").css("display", "none");
        }
    });

    textArea.addEventListener('keydown', function (event) {
        if (event.key === "Enter" && !event.ctrlKey && !event.shiftKey) {
            window.myLivewireHandler();
            event.preventDefault();
        } else if (event.key === "Enter" && (event.ctrlKey || event.shiftKey)) {
            const scrollHeight = this.scrollHeight;
            const scrollTop = this.scrollTop;
            const clientHeight = this.clientHeight;

            const currentValue = this.value;
            const selectionStart = this.selectionStart;
            const selectionEnd = this.selectionEnd;

            this.value = currentValue.substring(0, selectionStart) + '\n' + currentValue.substring(selectionEnd);
            this.selectionStart = this.selectionEnd = selectionStart + 1;

            // @todo show more lines in textarea
            // if(textArea.rows < 6) {
            //     textArea.rows += 1;
            // }

            if (scrollTop + clientHeight >= scrollHeight) {
                this.scrollTop = scrollHeight;
            }

            event.preventDefault();
        }
    });
});

/* Events */

window.addEventListener("DOMContentLoaded", (event) => {
    window.addEventListener('reloadChatPage', event=>{
        location.reload();
    });

    document.addEventListener('visibilitychange', event => {
        if (!document.hidden) {
            document.querySelector('link[rel="icon"]').href = '/images/free-icon-chat-bubble-6068634.png';
        }
    });
});



