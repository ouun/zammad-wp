=== Zammad for WordPress ===
Contributors: 		werepack, philippmuenchen
Donate link: 		https://www.paypal.me/ouun
Tags:				zammad, live-chat, ticketing, forms, feedback
Requires at least: 4.5
Requires PHP:      7.2
Tested up to:      6.1
Stable tag:        0.9.0

This plugin helps you embed Zammad Chats & Forms into your WordPress site and gives you access to the Zammad API if required.

== Description ==

Please see the [GitHub Repository](https://github.com/ouun/zammad-wp) for a complete documentation.

== Installation ==

= Composer Package (recommended) =

Use `composer require ouun/zammad-wp` to install plugin and dependencies.

= Manual Installation =

Download latest version and install it as a regular WordPress Plugin from:
https://github.com/ouun/zammad-wp/releases/latest/download/zammad-wp.zip

1. Upload the entire `/zammad-wp` directory to the `/wp-content/plugins/` directory.
2. Activate Zammad for WordPress through the 'Plugins' menu in WordPress.
3. Follow instructions in the [Wiki](https://github.com/ouun/zammad-wp/wiki).

== Changelog ==

= 0.9.0 =
* Enh: Forms now attach uploaded files to Zammad tickets
* Enh: Update Dependencies
* Fix: Forms: Fix handling if singe name field only contains single word

= 0.8.3 =
* Enh: Adds(test) support for GitHub Updater: https://github.com/afragen/github-updater
* Fix: Vendor folder missing from compiled download ZIP

= 0.8.2 =
* Various fixes

= 0.8.1 =
* Various fixes
* Move documentation to Wiki: https://github.com/ouun/zammad-wp/wiki
* Exit Pre-Release state on GitHub

= 0.8.0 =
* Adds HTML Forms Plugin integration
* Adds support for complex forms with custom fields
* Adds custom html/form as chat fallback
* Extends, fixes & improves ZammadWP API-Wrapper
* Various smaller fixes

= 0.7.0 =
* Fix mobile button alignment
* Various small bug fixes

= 0.6.0 =
* Minimize chat window while an active chat when clicking X
* Prevent default behavior to close the connection while chatting by accident

= 0.5.0 =
* Adds more options to set custom messages

= 0.4.0 =
* Various fixes

= 0.3.0 =
* Fixes Chat Fallback to Form
* Fixes missing form in backend

= 0.2.0 =
* Adds missing dist folder

= 0.1.0 =
* First release

== Upgrade Notice ==

= 0.1.0 =
First Release
