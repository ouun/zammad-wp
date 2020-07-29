<?php

/**
 * Registers a form
 *
 * @param string $form_element DOM element the form is attached to
 * @param array $args form options
 */
function zammad_register_form($form_element = '#feedback-form', $args = [])
{
	if (is_admin()) {
		add_action('admin_enqueue_scripts', function () use ($form_element, $args) {
			zammad_init_form($form_element, $args);
		}, 100);
	} else {
		add_action('wp_enqueue_scripts', function () use ($form_element, $args) {
			zammad_init_form($form_element, $args);
		}, 100);
	}
}

/**
 * Init function to add Zammad form. Loads required scripts.
 * Load it after 'admin_enqueue_scripts' or 'wp_enqueue_scripts'
 *
 * @param string $form_element DOM element the form is attached to
 * @param array $args form options
 */
function zammad_init_form($form_element = '#feedback-form', $args = [])
{
	// Zammad defaults, overwrite with filter 'zammad_wp:form:defaults'
	$defaults = apply_filters('zammad_wp:form:defaults', [
		'formElement' => $form_element,
		'debug' => false,
		'showTitle' => true,
		'messageTitle' => 'Contact us',
		'messageSubmit' => 'Submit',
	    'messageThankYou' => 'Thank you for your inquiry (#%s)! We\'ll contact you as soon as possible.',
	    'modal' => true,
	    'attachmentSupport' => true,
		'noCSS' => true # Loading the CSS from the plugin
	]);

	// Localize Script
	wp_localize_script('zammad_wp_form', 'formOptions', wp_parse_args($args, $defaults));

	//  Enqueue styles
	wp_enqueue_style('zammad_wp_form');

	// Zammad Form Script requires id "zammad_form_script"
	add_filter('script_loader_tag', function ($tag, $handle, $source) {
		if ($handle === 'zammad_form') {
			$tag = '<script id="zammad_form_script" src="' . $source . '"></script>';
		}
		return $tag;
	}, 10, 3);

	// Enqueue scripts
	wp_enqueue_script('zammad_form');
	wp_enqueue_script('zammad_wp_form');
}
