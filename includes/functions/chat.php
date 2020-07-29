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
	$defaults = apply_filters('zammad_wp:chat:defaults', [
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
	]);

	// Localize Script
	wp_localize_script('zammad_wp_chat', 'chatOptions', wp_parse_args($args, $defaults));

	//  Enqueue styles
	wp_enqueue_style('zammad_wp_chat');

	// Enqueue scripts
	wp_enqueue_script('zammad_chat');
	wp_enqueue_script('zammad_wp_chat');
}
