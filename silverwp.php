<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/padalec/silverwp.git
 * @since             0.1
 * @package           SilverWp
 * @category          wordpress-plugin
 *
 * @wordpress-plugin
 * Plugin Name:       SilverWp
 * Description:       SilverWp is a framework to help developers create themes or plugins
 * Version:           0.1
 * Author:            Michal Kalkowski
 * Author URI:        http://www.silversite.pl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       silverwp
 * Domain Path:       /languages
 */
namespace SilverWp;
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

require_once 'vendor/autoload.php';
try {
	SilverWp::getInstance()->isPlugin( new \SilverWp\Plugin() );

	$views  = SILVERWP_LIBS_PATH . 'ssvafpress/views';
	$assets_uri = plugin_dir_url( __FILE__ ) . 'assets';

	FileSystem::getInstance()->addDirectory( 'assets_uri', $assets_uri );
	FileSystem::getInstance()->addDirectory( 'views', $views );

	Translate::$language_path = plugin_dir_path( __FILE__ ) . 'languages/';
	Translate::$text_domain = 'silverwp';
	Translate::init();

} catch ( \SilverWp\Exception $ex ) {
    $ex->catchException();
}