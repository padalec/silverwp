<?php
/*
 * This file tends to be included in any development.
 * In a sentence, in every case where you don't want to use WP-SCSS as a standalone.
 *
 * Once included, it's up to you to use the available toolkit for your needs.
 *
 * = How to use? =
 *
 * 1. In your theme, include the `wp-scss` anywhere you want. (eg: `wp-content/themes/yourtheme/lib/wp-scss`)
 * 2. Include the required files in your functions.php file. (eg: `require dirname(__FILE__).'/lib/wp-scss/bootstreap-theme.php`)
 * 3. The `$WPScssPlugin` is available for your
 *
 * In case you need to access the $WPScssPlugin variable outside the include scope, simply do that:
 * `$WPScssPlugin = WPScssPlugin::getInstance();`
 *
 * And to apply automatic building on page display:
 * `add_action('wp_print_styles', array($WPScssPlugin, 'processStylesheets'));`
 * Or apply all hooks with:
 * `$WPScssPlugin->dispatch();`
 *
 * You can rebuild all stylesheets at any time with:
 * `$WPScssPlugin->processStylesheets();`
 *
 * Or a specific stylesheet:
 * `wp_enqueue_style('my_css', 'path/to/my/style.css');`
 * `$WPScssPlugin->processStylesheet('my_css');`
 *
 * = Filters and hooks aren't enough =
 *
 * Build your own flavour and manage it the way you want. Simply extends WPScssPlugin and/or WPScssConfiguration.
 * Dig in the code to see what to configure. I tried to make things customizable without extending classes!
 */

/*
 * This will be effective only if the plugin is not activated.
 * You can then redistribute your theme with this loader fearscssly.
 */
if (!class_exists('WPScssPlugin'))
{
  require dirname(__FILE__).'/lib/Plugin.class.php';
  $WPScssPlugin = WPPluginToolkitPlugin::create('WPScss', __FILE__, 'WPScssPlugin');

	//READY and WORKING
	//add_action('after_setup_theme', array($WPScssPlugin, 'install'));

	// NOT WORKING
	//@see http://core.trac.wordpress.org/ticket/14955
	//add_action('uninstall_theme', array($WPScssPlugin, 'uninstall'));
}
