<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://silversite.pl
 * @since             0.1
 * @package           SilverWp
 *
 * @wordpress-plugin
 * Plugin Name:       SilverWp
 * Description:       SilverWp is a framework to help developers create themes or plugins
 * Version:           0.1
 * Author:            SilverSite.pl
 * Author URI:        http://silversite.pl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       silverwp
 * Domain Path:       /languages
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

require_once 'vendor/autoload.php';
try {
    \SilverWp\SilverWp::getInstance()->isPlugin( new \SilverWp\Plugin() );
} catch ( \SilverWp\Exception $ex ) {
    $ex->catchException();
}