/* global formOptions:true */
jQuery(function initChat($) {
	$(formOptions.formElement).ZammadForm({
		messageTitle: formOptions.messageTitle,
		messageSubmit: formOptions.messageSubmit,
		messageThankYou: formOptions.messageThankYou,
		debug: formOptions.debug,
		showTitle: formOptions.showTitle,
		modal: formOptions.modal,
		noCSS: formOptions.noCSS,
		attachmentSupport: formOptions.attachmentSupport,
	});
});
