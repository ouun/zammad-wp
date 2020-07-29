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
			add_action('wp_footer', function () {
				echo '<div id="fallback-form" style="display: none;"></div>';
			}, 999);

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
		'debug' => false,
		'show' => true,
		'flat' => false,
		'chatID' => $chat_id,
		'chatTitle' => "<strong>Chat</strong> with us!",
		'fontSize' => null,
		'background' => '#0073aa',
		'buttonClass' => 'open-zammad-chat',
		'inactiveClass' => 'is-inactive',
		'cssAutoload' => false,
		'cssUrl ' => null,
		'formFallback' => false,
	]));

	// Localize Script
	wp_localize_script('zammad_wp_chat', 'chatOptions',  $settings);

	//  Enqueue styles
	wp_enqueue_style('zammad_wp_chat');

	// Enqueue scripts
	wp_enqueue_script('zammad_chat');
	wp_enqueue_script('zammad_wp_chat');
}
