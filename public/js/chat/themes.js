function setTheme(themeId) {
    const theme = stylesConfig.themes[themeId];

    document.documentElement.style.setProperty('--back-for-chat-color', theme.backForChatColor);
    document.documentElement.style.setProperty('--back-for-chat-info-color', theme.backForChatInfoColor);
    document.documentElement.style.setProperty('--main-text-color', theme.mainTextColor);
    document.documentElement.style.setProperty('--second-text-color', theme.secondTextColor);
    document.documentElement.style.setProperty('--back-message-color', theme.backMessageColor);
    document.documentElement.style.setProperty('--back-search-send-form-color', theme.backSearchSendFormColor);
    document.documentElement.style.setProperty('--back-text-area-color', theme.backTextAreaColor);
    document.documentElement.style.setProperty('--hover-color', theme.hoverColor);
    document.documentElement.style.setProperty('--placeholder-color', theme.placeholderColorChat);
    document.documentElement.style.setProperty('--placeholder-search-color', theme.placeholderSearchColor);
}
