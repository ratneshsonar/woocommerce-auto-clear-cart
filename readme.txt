=== WooCommerce Auto Clear Cart ===
Contributors: ratneshsonar
Tags: woocommerce, cart, session, auto-clear, cron
Requires at least: 5.6
Tested up to: 6.5
Requires PHP: 7.2
Stable tag: 1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically clears WooCommerce cart and session data at set intervals (15, 30, or 60 minutes). Optionally log the clearing. Admin controlled.

== Description ==

This plugin allows WooCommerce store owners to automatically clear the cart and session data every 15, 30, or 60 minutes. Designed for performance and cleanups in stores where cart bloat can occur.

**Features:**
* Admin settings page
* Set interval: 15 / 30 / 60 minutes
* Toggle auto-clear on/off
* Optional logging to file
* Secure and performance-friendly

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/woocommerce-auto-clear-cart` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the ‘Plugins’ screen in WordPress
3. Go to **WooCommerce → Auto Clear Cart** to configure.

== Screenshots ==
1. Admin settings screen with options to enable/disable, interval and logging

== Changelog ==

= 1.2 =
* Added WooCommerce dependency check and auto-deactivation
* Added file logging with writable check
* Sanitized and escaped inputs
* Cleaned up cron scheduling

= 1.0 =
* Initial release with basic cron-based session clearing

== Upgrade Notice ==

= 1.2 =
Security hardening and logging improvements. Please update.

== Frequently Asked Questions ==

= Does it affect users actively checking out? =
Yes, if a user leaves their cart idle and the cron job runs, it will clear their session.

= Can I disable the feature temporarily? =
Yes, go to WooCommerce → Auto Clear Cart and set "Disable" to pause it.

== License ==

This plugin is licensed under GPLv2 or later.
