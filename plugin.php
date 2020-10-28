<?php

/**
 * Plugin Name:         Zammad for WordPress
 * Plugin URI:          https://ouun.io
 * Description:         Integrates Zammad Helpdesk into WordPress
 * Author:              Philipp Wellmer <philipp@ouun.io>
 * License:             GPL v2 or later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP:        7.2
 * Text Domain:         zammad-wp
 * Domain Path:         /languages
 *
 * @package ZammadWp
 */

// Useful global constants.
define('ZAMMAD_WP_VERSION', '0.7.0');
define('ZAMMAD_WP_URL', plugin_dir_url(__FILE__));
define('ZAMMAD_WP_PATH', plugin_dir_path(__FILE__));
define('ZAMMAD_WP_INC', ZAMMAD_WP_PATH . 'includes/');

// Include files.
require_once ZAMMAD_WP_INC . 'functions/core.php';
require_once ZAMMAD_WP_INC . 'functions/chat.php';
require_once ZAMMAD_WP_INC . 'functions/form.php';
require_once ZAMMAD_WP_INC . 'functions/hf-plugin-integration.php';

// Activation/Deactivation.
register_activation_hook(__FILE__, '\ZammadWp\Core\activate');
register_deactivation_hook(__FILE__, '\ZammadWp\Core\deactivate');

// Bootstrap.
ZammadWp\Core\setup();

// Require Composer autoloader if it exists.
if (file_exists(ZAMMAD_WP_PATH . '/vendor/autoload.php')) {
    require_once ZAMMAD_WP_PATH . 'vendor/autoload.php';
}
