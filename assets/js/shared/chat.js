/* global ZammadChat chatOptions:true */
jQuery(function initChat($) {
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

	if (chatOptions.formFallback) {
		const form = $('#fallback-form');

		if (form.length && chat) {
			// On ERROR: E.g. there is no agent online
			chat.onError = () => {
				// Update chat badge title from
				const badgeTitle = form.find('h2').hide();
				$('.zammad-chat-welcome-text').text(badgeTitle.text());

				// Form button add class
				form.find('.btn').addClass('button');

				// Show chat badge
				chat.show();

				// Add form on open
				chat.onOpenAnimationEnd = () => {
					// Close session & destroy to avoid reconnection
					chat.sessionClose();
					chat.destroy();

					// Add the form
					form.show();
					jQuery('.zammad-chat-modal').addClass('zammad-fallback-form').html(form);

					// Desroying the chat destroys the open/close functionality
					$('.zammad-chat .zammad-chat-header').on(
						'click touchstart',
						function toggleZammadChat() {
							const zammadChat = $(this).parent('.zammad-chat');

							if (zammadChat.hasClass('zammad-chat-is-open')) {
								zammadChat.removeClass('zammad-chat-is-open');
							} else {
								zammadChat.addClass('zammad-chat-is-open');
							}
						},
					);
				};
			};
		}
	}
});
