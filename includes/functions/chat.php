<?php

/**
 * Registers a chat
 *
 * @param int $chat_id Zammad Chat-Topic ID
 * @param array $args Chat options
 */
function zammad_register_chat($chat_id = 1, $args = array())
{
    if (is_admin()) {
        add_action(
            'admin_enqueue_scripts',
            function () use ($chat_id, $args) {
                zammad_init_chat($chat_id, $args);
            },
            100
        );
    } else {
        add_action(
            'wp_enqueue_scripts',
            function () use ($chat_id, $args) {
                zammad_init_chat($chat_id, $args);
            },
            100
        );
    }

	do_action( 'zammad_wp:fallback_form:before', $args );

    // A chat with form fallback requires additional form init, too.
    if (isset($args['formFallback']) && $args['formFallback']) {
    	if( !isset( $args['formFallbackHTML'] ) || empty( $args['formFallbackHTML'] ) ) {
    		// We use native Zammad Form
		    if (function_exists('zammad_register_form')) {
			    add_action('wp_footer', 'zammad_fallback_form_markup', 999);
			    add_action('admin_footer', 'zammad_fallback_form_markup', 999);

			    zammad_register_form(
				    '#fallback-form',
				    apply_filters(
					    'zammad_wp:form:fallback',
					    array(
						    'modal' => false,
					    )
				    )
			    );
		    }
	    } else {
    		// Add custom markup before any other filter
    		add_filter('zammad_wp:fallback_form:html', function () use ($args) {
    			return $args['formFallbackHTML'];
		    }, 5);

		    add_action('wp_head', 'zammad_fallback_form_markup', 999);
		    add_action('admin_head', 'zammad_fallback_form_markup', 999);
	    }
    }

	do_action( 'zammad_wp:fallback_form:after', $args );
}

/**
 * Init function to add Zammad chat. Loads required scripts.
 * Load it after 'admin_enqueue_scripts' or 'wp_enqueue_scripts'
 *
 * @param int $chat_id Chat ID
 * @param array $args Chat options
 */
function zammad_init_chat($chat_id = 1, $args = array())
{
    // Zammad defaults, overwrite with filter 'zammad_wp:chat:defaults'
    $settings = wp_parse_args(
        $args,
        apply_filters(
            'zammad_wp:chat:defaults',
            array(
                'debug'                     => (bool) false,
                'show'                      => (bool) true,
                'flat'                      => (bool) false,
                'chatID'                    => $chat_id,
                'chatTitle'                 => '<strong>Chat</strong> with us!',
                'fontSize'                  => null,
                'background'                => '#0073aa',
                'buttonClass'               => 'open-zammad-chat',
                'inactiveClass'             => 'is-inactive',
                'cssAutoload'               => (bool) false,
                'cssUrl '                   => null,
                'timeout'                   => '0.4', // Minutes to wait till timeout when waiting for an agents answer
                'timeoutIntervallCheck'     => '0.5', // Interval to check for timeout
                'formFallback'              => (bool) false,
                'formFallbackMessage'       => __('Please send us your request and we will answer as soon as possible', 'zammad-wp'),
                'loaderWaitingMessage'      => '',
                'waitingListWaitingMessage' => __('Sorry for the delay, still connecting.', 'zammad-wp'),
                'waitingListTimeoutMessage' => sprintf(
                    // translators: Placeholder is "click here" link text
                    __(
                        'We do not want to let you wait anytime longer. Please feel free to use the form instead or <a class="js-restart" href="#restart">%s</a> to try again.',
                        'zammad-wp'
                    ),
                    __('click here', 'zammad-wp')
                ),
            )
        )
    );

    // Localize Script
    wp_add_inline_script('zammad_wp_chat', 'const chatOptions =' . json_encode($settings), 'before');

    //  Enqueue styles
    wp_enqueue_style('zammad_wp_chat');

    // Enqueue scripts
    wp_enqueue_script('zammad_chat');
    wp_enqueue_script('zammad_wp_chat');
}

/**
 * The Fallback Form Markup added to the DOM if Fallback is active
 * In JS we load the form into #fallback-form element
 *
 * @param string $html
 */
function zammad_fallback_form_markup($html = '')
{
	$html = apply_filters( 'zammad_wp:fallback_form:html', $html );
    echo '<div id="fallback-form" style="display: none;">' . $html . '</div>';
}
