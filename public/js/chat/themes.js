function setTheme(themeId) {
    // const theme = stylesConfig.themes[themeId];

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

    switch (themeId) {
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
}
