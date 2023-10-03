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

/* Scroll */
/* Move to blades */

// function scrollToBottom() {
//     let chatMessages = document.getElementById('messages');
//
//
//     chatMessages.scrollTop = chatMessages.scrollHeight;
// }
//
// function scrollToCurrentMessage() {
//     let chatMessages = document.getElementById('messages');
//
//     // chatMessages.scrollTop = chatMessages.scrollHeight - previousScrollHeight;
// }


/* Notifications */


function notifyMe(json) {
    if (!("Notification" in window)) {
        alert("This browser does not support desktop notification");
    } else if (Notification.permission === "granted") {
        const notification = new Notification("Chat", {
            body: 'New message from ' + json.user.name,
            icon: '/images/free-icon-chat-bubble-6068634.png',
        });

        document.querySelector('link[rel="icon"]').href = '/images/free-icon-chat-bubble-6068634- new-messages.png';

    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then((permission) => {
            if (permission === "granted") {
                const notification = new Notification("Chat", {
                    body: 'New message from ' + json.user.name,
                    icon: '/images/free-icon-chat-bubble-6068634.png',
                });

                document.querySelector('link[rel="icon"]').href = '/images/free-icon-chat-bubble-6068634- new-messages.png';
            }
        });
    }
}


/* Events */

window.addEventListener("DOMContentLoaded", (event) => {
    let textArea = document.getElementById('text');
    let sendButton = document.getElementById('send');

    textArea.addEventListener('input', function () {
        if (textArea.value.trim() !== '') {
            sendButton.removeAttribute('disabled');
        } else {
            sendButton.setAttribute('disabled', 'disabled');
        }
    });
});



