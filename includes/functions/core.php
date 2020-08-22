<?php

/**
 * Core plugin functionality.
 *
 * @package ZammadWp
 */

namespace ZammadWp\Core;

use WP_Error as WP_Error;

/**
 * Default setup routine
 *
 * @return void
 */
function setup()
{
    $n = function ($function) {
        return __NAMESPACE__ . "\\$function";
    };

    add_action('init', $n('i18n'));
    add_action('init', $n('init'));
    add_action('wp_enqueue_scripts', $n('scripts'));
    add_action('wp_enqueue_scripts', $n('styles'));
    add_action('admin_enqueue_scripts', $n('admin_scripts'));
    add_action('admin_enqueue_scripts', $n('admin_styles'));

    // Hook to allow async or defer on asset loading.
    add_filter('script_loader_tag', $n('script_loader_tag'), 10, 2);

    do_action('zammad_wp_loaded');
}

/**
 * Registers the default textdomain.
 *
 * @return void
 */
function i18n()
{
    $locale = apply_filters('plugin_locale', get_locale(), 'zammad-wp');
    load_textdomain('zammad-wp', WP_LANG_DIR . '/zammad-wp/zammad-wp-' . $locale . '.mo');
    load_plugin_textdomain('zammad-wp', false, plugin_basename(ZAMMAD_WP_PATH) . '/languages/');
}

/**
 * Initializes the plugin and fires an action other plugins can hook into.
 *
 * @return void
 */
function init()
{
    do_action('zammad_wp_init');
}

/**
 * Activate the plugin
 *
 * @return void
 */
function activate()
{
    // First load the init scripts in case any rewrite functionality is being loaded
    init();
    flush_rewrite_rules();
}

/**
 * Deactivate the plugin
 *
 * Uninstall routines should be in uninstall.php
 *
 * @return void
 */
function deactivate()
{
}


/**
 * The list of knows contexts for enqueuing scripts/styles.
 *
 * @return array
 */
function get_enqueue_contexts()
{
    return array( 'admin', 'frontend', 'shared' );
}

/**
 * Generate an URL to a script, taking into account whether SCRIPT_DEBUG is enabled.
 *
 * @param string $script Script file name (no .js extension)
 * @param string $context Context for the script ('admin', 'frontend', or 'shared')
 *
 * @return string|WP_Error URL
 */
function script_url($script, $context)
{

    if (! in_array($context, get_enqueue_contexts(), true)) {
        return new WP_Error('invalid_enqueue_context', 'Invalid $context specified in ZammadWp script loader.');
    }

    return ZAMMAD_WP_URL . "dist/js/${script}.js";
}

/**
 * Generate an URL to a stylesheet, taking into account whether SCRIPT_DEBUG is enabled.
 *
 * @param string $stylesheet Stylesheet file name (no .css extension)
 * @param string $context Context for the script ('admin', 'frontend', or 'shared')
 *
 * @return string URL
 */
function style_url($stylesheet, $context)
{

    if (! in_array($context, get_enqueue_contexts(), true)) {
        return new WP_Error('invalid_enqueue_context', 'Invalid $context specified in ZammadWp stylesheet loader.');
    }

    return ZAMMAD_WP_URL . "dist/css/${stylesheet}.css";
}

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts()
{
    wp_register_script(
        'zammad_chat',
        ZAMMAD_URL . '/assets/chat/chat.min.js',
        array( 'jquery' ),
        ZAMMAD_WP_VERSION,
        true
    );

    wp_register_script(
        'zammad_wp_chat',
        script_url('chat', 'shared'),
        array(),
        ZAMMAD_WP_VERSION,
        true
    );

    wp_register_script(
        'zammad_form',
        ZAMMAD_URL . '/assets/form/form.js',
        array( 'jquery' ),
        ZAMMAD_WP_VERSION,
        true
    );

    wp_register_script(
        'zammad_wp_form',
        script_url('form', 'shared'),
        array( 'jquery', 'zammad_form' ),
        ZAMMAD_WP_VERSION,
        true
    );

    /*
     * Required for further development
     *
     */
    /*
    wp_enqueue_script(
        'zammad_wp_shared',
        script_url('shared', 'shared'),
        [],
        ZAMMAD_WP_VERSION,
        true
    );

    wp_enqueue_script(
        'zammad_wp_frontend',
        script_url('frontend', 'frontend'),
        [],
        ZAMMAD_WP_VERSION,
        true
    );
    */
}

/**
 * Enqueue scripts for admin.
 *
 * @return void
 */
function admin_scripts()
{
    wp_register_script(
        'zammad_chat',
        ZAMMAD_URL . '/assets/chat/chat.min.js',
        array( 'jquery' ),
        ZAMMAD_WP_VERSION,
        true
    );

    wp_register_script(
        'zammad_wp_chat',
        script_url('chat', 'shared'),
        array( 'jquery', 'zammad_chat' ),
        ZAMMAD_WP_VERSION,
        true
    );

    wp_register_script(
        'zammad_form',
        ZAMMAD_URL . '/assets/form/form.js',
        array( 'jquery' ),
        ZAMMAD_WP_VERSION,
        true
    );

    wp_register_script(
        'zammad_wp_form',
        script_url('form', 'shared'),
        array( 'jquery', 'zammad_form' ),
        ZAMMAD_WP_VERSION,
        true
    );

    /*
     * Required for further development
     *
     */
    /*
    wp_enqueue_script(
        'zammad_wp_shared',
        script_url('shared', 'shared'),
        [],
        ZAMMAD_WP_VERSION,
        true
    );

    wp_enqueue_script(
        'zammad_wp_admin',
        script_url('admin', 'admin'),
        [],
        ZAMMAD_WP_VERSION,
        true
    );
    */
}

/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function styles()
{
    wp_register_style(
        'zammad_wp_chat',
        style_url('chat-style', 'shared'),
        array(),
        ZAMMAD_WP_VERSION
    );

    wp_register_style(
        'zammad_wp_form',
        style_url('form-style', 'shared'),
        array(),
        ZAMMAD_WP_VERSION
    );

    /*
     * Required for further development
     *
     */
    /*
    wp_enqueue_style(
        'zammad_wp_shared',
        style_url('shared-style', 'shared'),
        [],
        ZAMMAD_WP_VERSION
    );

    if (is_admin()) {
        wp_enqueue_style(
            'zammad_wp_admin',
            style_url('admin-style', 'admin'),
            [],
            ZAMMAD_WP_VERSION
        );
    } else {
        wp_enqueue_style(
            'zammad_wp_frontend',
            style_url('style', 'frontend'),
            [],
            ZAMMAD_WP_VERSION
        );
    }
    */
}

/**
 * Enqueue styles for admin.
 *
 * @return void
 */
function admin_styles()
{
    wp_register_style(
        'zammad_wp_chat',
        style_url('chat-style', 'shared'),
        array(),
        ZAMMAD_WP_VERSION
    );

    wp_register_style(
        'zammad_wp_form',
        style_url('form-style', 'shared'),
        array(),
        ZAMMAD_WP_VERSION
    );

    /*
     * Required for further development
     *
     */
    /*
    wp_enqueue_style(
        'zammad_wp_shared',
        style_url('shared-style', 'shared'),
        [],
        ZAMMAD_WP_VERSION
    );

    wp_enqueue_style(
        'zammad_wp_admin',
        style_url('admin-style', 'admin'),
        [],
        ZAMMAD_WP_VERSION
    );
    */
}

/**
 * Add async/defer attributes to enqueued scripts that have the specified script_execution flag.
 *
 * @link https://core.trac.wordpress.org/ticket/12009
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string
 */
function script_loader_tag($tag, $handle)
{
    $script_execution = wp_scripts()->get_data($handle, 'script_execution');

    if (! $script_execution) {
        return $tag;
    }

    if ('async' !== $script_execution && 'defer' !== $script_execution) {
        return $tag; // _doing_it_wrong()?
    }

    // Abort adding async/defer for scripts that have this script as a dependency. _doing_it_wrong()?
    foreach (wp_scripts()->registered as $script) {
        if (in_array($handle, $script->deps, true)) {
            return $tag;
        }
    }

    // Add the attribute if it hasn't already been added.
    if (! preg_match(":\s$script_execution(=|>|\s):", $tag)) {
        $tag = preg_replace(':(?=></script>):', " $script_execution", $tag, 1);
    }

    return $tag;
}
