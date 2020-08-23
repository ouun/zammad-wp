/* global ZammadChat chatOptions:true */
jQuery(function initForm($) {
	const form = $('#fallback-form');

	/**
	 * Console debug helper
	 *
	 * @param {string} message Message to log in console
	 * @param {boolean} plain Log without prepending a string
	 */
	function zammadDebugMessage(message, plain = false) {
		if (chatOptions.debug) {
			console.log(!plain ? `DEBUG Zammad - WP : ${message}` : message); // eslint-disable-line no-console
		}
	}

	/**
	 * Modify chat modal text
	 *
	 * @param {string} newText New text displayed in the chat modal, e.g. while waiting for an agent
	 */
	function zammadChatModalText(newText) {
		$('.zammad-chat-modal-text').text(newText);
	}

	/**
	 * Updates the title of the Chat Badge
	 *
	 * @param {string} newTitle New label of the chat badge
	 */
	function zammadChatBadgeTitle(newTitle = null) {
		// If no title is passed, use the form title
		const badgeTitle = newTitle || form.find('h2').text();

		// Hide the form title
		form.find('h2').hide();

		// Update Chat Badge Title
		$('.zammad-chat-welcome-text').text(badgeTitle);
	}

	/**
	 * Loads the fallback form into the chat window
	 *
	 * @param {string} prependToForm String or HTML to prepend to the form
	 */
	function zammadDisplayFallbackForm(prependToForm = '') {
		// Prepend HTML to form
		form.prepend(prependToForm);

		// Form button add class
		form.find('.btn').addClass('button');

		$('.zammad-chat-modal').addClass('zammad-fallback-form').html(form);

		// Destroying the chat destroys the open/close functionality
		$('.zammad-chat .zammad-chat-header').on('click touchstart', function toggleZammadChat() {
			const zammadChat = $(this).parent('.zammad-chat');

			if (zammadChat.hasClass('zammad-chat-is-open')) {
				zammadChat.removeClass('zammad-chat-is-open');
			} else {
				zammadChat.addClass('zammad-chat-is-open');
			}
		});

		// Add the form
		form.show();
	}

	/**
	 * Zammad Chat Init
	 */
	function zammadInitChat() {
		if ($.isFunction(window.ZammadChat)) {
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
				zammadDebugMessage('Chat is set-up:');
				zammadDebugMessage(chat, true);
				zammadDebugMessage(
					`Form fallback is turned ${chatOptions.formFallback ? 'on' : 'off'}`,
				);
			}

			if (chatOptions.formFallback) {
				zammadDebugMessage('Look for the Form.');

				if (form.length && chat) {
					zammadDebugMessage('Fallback Form is available!');

					// On ERROR: E.g. there is no agent online
					chat.onError = () => {
						zammadDebugMessage('No agent online. Get the fallback!');

						// Update Chat Badge Title
						zammadChatBadgeTitle();

						// Show chat badge
						chat.show();

						// Add form on open
						chat.onOpenAnimationEnd = () => {
							// Close session & destroy to avoid reconnection
							chat.sessionClose();
							chat.destroy();

							// Display Fallback Form
							zammadDisplayFallbackForm(chatOptions.formFallbackMessage);
						};
					};

					chat.onQueue = () => {
						zammadDebugMessage('Waiting for an agent to answer...');
						zammadDebugMessage(chat, true);

						// Reducing the time we wait for an agent to answer
						chat.waitingListTimeout.options.timeout = '0';
						chat.waitingListTimeout.options.timeoutIntervallCheck = '0.2';

						// Let the user know that we are waiting
						zammadChatModalText(chatOptions.waitingListWaitingMessage);
					};

					chat.showWaitingListTimeout = () => {
						zammadDebugMessage('No answer, show the form and close connection...');

						// Display Fallback Form, prepend a message
						zammadChatBadgeTitle();
						zammadDisplayFallbackForm(chatOptions.waitingListTimeoutMessage);

						// Add reload functionality e.g. to buttons
						form.find('.js-restart').on('click', function zammadReloadWindow() {
							zammadDebugMessage('Reload Window...');
							window.location.reload();
						});

						// Close the session
						return chat.sessionClose();
					};
				} else if (!form.length) {
					zammadDebugMessage('The Fallback Form is missing. Maybe a caching issue?');
				}
			}
		} else {
			// Error: chat.js not available from Zammad
			zammadDebugMessage('Zammad Server or remote JS is not available.');
		}
	}

	/**
	 * Build the Chat
	 */
	zammadInitChat();
});
