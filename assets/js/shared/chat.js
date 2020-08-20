/* global ZammadChat chatOptions:true */
function zammadDebugMessage(message) {
	if (chatOptions.debug) {
		console.log(`DEBUG Zammad-WP: ${message}`); // eslint-disable-line no-console
	}
}

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

	if (chat) {
		zammadDebugMessage('Chat is set-up.');
		zammadDebugMessage(`Form fallback is turned ${chatOptions.formFallback ? 'on' : 'off'}`);
	}

	if (chatOptions.formFallback) {
		zammadDebugMessage('Look for the Form.');
		const form = $('#fallback-form');

		if (form.length && chat) {
			zammadDebugMessage('Fallback Form is available!');

			// On ERROR: E.g. there is no agent online
			chat.onError = () => {
				zammadDebugMessage('No agent online. Get the fallback!');

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

					// Destroying the chat destroys the open/close functionality
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
