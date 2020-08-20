<?php

/**
 * Registers a chat
 *
 * @param int $chat_id Zammad Chat-Topic ID
 * @param array $args Chat options
 */
function zammad_register_chat($chat_id = 1, $args = [])
{
	if (is_admin()) {
		add_action('admin_enqueue_scripts', function () use ($chat_id, $args) {
			zammad_init_chat($chat_id, $args);
		}, 100);
	} else {
		add_action('wp_enqueue_scripts', function () use ($chat_id, $args) {
			zammad_init_chat($chat_id, $args);
		}, 100);
	}

	// A chat with form fallback requires additional form init, too.
	if(isset($args['formFallback']) && $args['formFallback']) {
		if(function_exists('zammad_register_form')) {
			add_action('wp_footer', 'zammad_fallback_form_markup', 999);
			add_action('admin_footer', 'zammad_fallback_form_markup', 999);

			zammad_register_form('#fallback-form', apply_filters('zammad_wp:form:fallback', [
				'modal' => false
			]));
		}
	}
}

/**
 * Init function to add Zammad chat. Loads required scripts.
 * Load it after 'admin_enqueue_scripts' or 'wp_enqueue_scripts'
 *
 * @param int $chat_id Chat ID
 * @param array $args Chat options
 */
function zammad_init_chat($chat_id = 1, $args = [])
{
	// Zammad defaults, overwrite with filter 'zammad_wp:chat:defaults'
	$settings = wp_parse_args($args, apply_filters('zammad_wp:chat:defaults', [
		'debug' => (bool) false,
		'show' => (bool) true,
		'flat' => (bool) false,
		'chatID' => $chat_id,
		'chatTitle' => "<strong>Chat</strong> with us!",
		'fontSize' => null,
		'background' => '#0073aa',
		'buttonClass' => 'open-zammad-chat',
		'inactiveClass' => 'is-inactive',
		'cssAutoload' => (bool) false,
		'cssUrl ' => null,
		'formFallback' => (bool) false,
	]));

	// Localize Script
	wp_add_inline_script('zammad_wp_chat', 'const chatOptions ='.json_encode($settings));

	//  Enqueue styles
	wp_enqueue_style('zammad_wp_chat');

	// Enqueue scripts
	wp_enqueue_script('zammad_chat');
	wp_enqueue_script('zammad_wp_chat');
}

/**
 * The Fallback Form Markup added to the DOM if Fallback is active
 * In JS we load the form into #fallback-form element
 */
function zammad_fallback_form_markup()
{
	echo '<div id="fallback-form" style="display: none;"></div>';
}
