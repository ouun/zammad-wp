<?php

/**
 * Example how to embed
 */

add_action('admin_init', function () {
    register_chat();
});

function register_chat($chat_id = 2, $show = true, $flat = false)
{
    add_action('admin_enqueue_scripts', function () use ($chat_id, $show, $flat) {
        add_chat($chat_id, $show, $flat);
    }, 100);
}

function add_chat($chat_id, $show, $flat)
{

    // Localize Script
    wp_localize_script('zammad_wp_chat', 'chatOptions', apply_filters('zammad_wp:chat:defaults', [
        'debug' => false,
        'chatTitle' => __('chattitel', 'zammad-wp'),
        'chatID' => $chat_id,
        'show' => $show,
        'flat' => $flat,
        'fontSize' => '12px',
        'buttonClass' => 'open-zammad-chat',
        'inactiveClass' => 'is-inactive',
        'cssAutoload ' => true,
        'cssUrl ' => null,
    ]));

    // Enqueue scripts
    wp_enqueue_script('zammad_chat');
    wp_enqueue_script('zammad_wp_chat');
}
