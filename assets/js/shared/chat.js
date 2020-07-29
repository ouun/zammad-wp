/* global ZammadChat chatOptions:true */
jQuery(function initChat() {
	const chat = new ZammadChat({
		debug: chatOptions.debug,
		title: chatOptions.chatTitle,
		fontSize: chatOptions.fontSize,
		chatId: chatOptions.chatID,
		show: chatOptions.show,
		flat: chatOptions.flat,
		background: chatOptions.background,
		buttonClass: chatOptions.buttonClass,
		inactiveClass: chatOptions.inactiveClass,
		cssAutoload: chatOptions.cssAutoload,
		cssUrl: chatOptions.cssUrl,
	});

	chat();
});
