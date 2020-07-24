jQuery(document).ready(function ($) {
	$(function () {
		new ZammadChat({
			debug: chatOptions.debug,
			// title: chatOptions.chatTitle,
			fontSize: chatOptions.fontSize,
			chatId: chatOptions.chatID,
			show: chatOptions.show,
			flat: chatOptions.flat,
			buttonClass: chatOptions.buttonClass,
			inactiveClass: chatOptions.inactiveClass,
			cssAutoload: chatOptions.cssAutoload,
			cssUrl: chatOptions.cssUrl,
		});
	});
});
