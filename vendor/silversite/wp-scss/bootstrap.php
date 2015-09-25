<?php
/*
Plugin Name: WP SCSS
Description: SCSS extends CSS with variables, mixins, operations and nested rules. This plugin magically parse all your <code>*.scss</code> files queued with <code>wp_enqueue_style</code> in WordPress.
Author: Oncle Tom
Version: 1.7.4
Author URI: https://oncletom.io/
Plugin URI: http://wordpress.org/extend/plugins/wp-scss/

  This plugin is released under version 3 of the GPL:
  http://www.opensource.org/licenses/gpl-3.0.html
*/

if (!class_exists('WPScssPlugin'))
{
	require dirname(__FILE__).'/lib/Plugin.class.php';
	$WPScssPlugin = WPPluginToolkitPlugin::create('WPScss', __FILE__, 'WPScssPlugin');

	register_activation_hook(__FILE__, array($WPScssPlugin, 'install'));
	register_deactivation_hook(__FILE__, array($WPScssPlugin, 'uninstall'));

	$WPScssPlugin->dispatch();
}
