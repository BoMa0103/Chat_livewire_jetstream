<div class="chat-input" style="z-index: 10">
    <form wire:submit.prevent='sendMessage'>
        <div class="flex items-center px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-700">
            <button type="button"
                    class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 chat_button">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 20 18">
                    <path fill="currentColor"
                          d="M13 5.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM7.565 7.423 4.5 14h11.518l-2.516-3.71L11 13 7.565 7.423Z"/>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M18 1H2a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z"/>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 5.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM7.565 7.423 4.5 14h11.518l-2.516-3.71L11 13 7.565 7.423Z"/>
                </svg>
                <span class="sr-only">Upload image</span>
            </button>
            <div class="emoji-dropdown">
                <button class="emoji-button p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 chat_button" type="button">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.408 7.5h.01m-6.876 0h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM4.6 11a5.5 5.5 0 0 0 10.81 0H4.6Z"/>
                    </svg>
                </button>
                <div class="emoji-menu">
                    <!-- Ваш список смайликов здесь -->
                    <div class="emoji-item">😀</div>
                    <div class="emoji-item">😍</div>
                    <div class="emoji-item">😂</div>
                    <!-- Другие смайлики -->
                </div>
            </div>
            <textarea wire:model="body" id="text" rows="1"
                      class="block mx-4 p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-gray-600 focus:border-gray-600 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-600 dark:focus:border-gray-600 chat_textarea"
                      placeholder="Your message..."></textarea>
            <button disabled id="send" type="submit"
                    class="inline-flex justify-center p-2 text-blue-600 rounded-full cursor-pointer hover:bg-blue-100 dark:text-blue-500 dark:hover:bg-gray-600 chat_button">
                <svg class="w-5 h-5 rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                     viewBox="0 0 18 20">
                    <path
                        d="m17.914 18.594-8-18a1 1 0 0 0-1.828 0l-8 18a1 1 0 0 0 1.157 1.376L8 18.281V9a1 1 0 0 1 2 0v9.281l6.758 1.689a1 1 0 0 0 1.156-1.376Z"/>
                </svg>
                <span class="sr-only">Send message</span>
            </button>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            var hideTimeout; // Переменная для хранения идентификатора таймера

            // При клике на кнопку, показать меню
            $(".emoji-dropdown .emoji-button").click(function () {
                clearTimeout(hideTimeout); // Очистить таймер скрытия меню, если он был запущен
                var $menu = $(this).siblings(".emoji-menu");
                if ($menu.css("display") === "none") {
                    $menu.css("display", "block");
                } else {
                    $menu.css("display", "none");
                }
            });

            function insertEmoji(emoji) {
                var textarea = document.getElementById('text');
                textarea.value += emoji;
                textarea.dispatchEvent(new Event('input'));
            }

            // Обработчик клика на смайлике
            $(".emoji-item").click(function () {
                var selectedEmoji = $(this).text();
                insertEmoji(selectedEmoji);
            });

            // Закрыть меню, если клик произошел за его пределами
            $(document).click(function (e) {
                if (!$(e.target).closest(".emoji-dropdown").length) {
                    $(".emoji-menu").css("display", "none");
                }
            });
        });
    </script>
</div>
