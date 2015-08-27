<?php
/*
 * Copyright (C) 2014 Michal Kalkowski <michal at silversite.pl>
 *
 * SilverWp is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * SilverWp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
namespace SilverWp;

use SilverWp\Interfaces\Plugin;

if ( ! class_exists( 'SilverWp' ) ) {

    /**
     *
     * Main core class loader
     *
     * @category  WordPress
     * @package   SilverWp
     * @author    Michal Kalkowski <michal at silversite.pl>
     * @copyright SilverSite.pl 2015
     * @version   $Revision:$
     */
    class SilverWp extends SingletonAbstract {
        /**
         * Framework version
         *
         * @var string
         * @access protected
         */
        protected $version = '0.1';

        /**
         *
         * Class constructor
         *
         * @access protected
         */
        protected function __construct() {
            $this->constant();
            $this->includeCore();
        }

        /**
         *
         * Constants
         *
         * @access private
         */
        private function constant() {

            defined( 'SILVERWP_VER' )
            || define( 'SILVERWP_VER', $this->version );

            define( 'SILVERWP_DIR', plugin_dir_path( __FILE__ ) );

            define( 'SILVERWP_THEME_TEXT_DOMAIN', 'silverwp' );

            define( 'SILVERWP_OPTION_PREFIX', '_silverwp_option' );

            defined( 'SILVERWP_META_BOX_DEV' )
            || define( 'SILVERWP_META_BOX_DEV', false );

            defined( 'THEME_OPTION_PREFIX' )
            || define( 'THEME_OPTION_PREFIX', '_silverwp_option' );

            defined( 'SILVERWP_META_BOX_DEV' )
            || define( 'SILVERWP_META_BOX_DEV', false );

            defined( 'SILVERWP_THEME_OPTIONS_DEV' )
            || define( 'SILVERWP_THEME_OPTIONS_DEV', true );

            defined( 'SILVERWP_LIBS_PATH' )
            || define( 'SILVERWP_LIBS_PATH', SILVERWP_DIR . 'libs/' );

            defined( 'SILVERWP_VENDOR_PATH' )
            || define( 'SILVERWP_VENDOR_PATH', SILVERWP_DIR . '../vendor/' );

            defined( 'SILVERWP_VENDOR_URI' )
            || define(
                    'SILVERWP_VENDOR_URI',
                    plugins_url( 'silverwp', 'silverwp' ) . '/vendor/'
                );
        }

        /**
         * Include library and all necessary files
         *
         * @throws \SilverWp\Exception
         * @access private
         */
        private function includeCore() {
            if ( ! version_compare( PHP_VERSION, '5.3.2', '>=' ) ) {
                throw new Exception( include( 'function/php_version_warning_message.php' ) );
            }
            require_once 'function/functions.php';
            $this->vpFix();
        }

        /**
         * If framework will be used in plugin this method register necessary hooks
         *
         * @param \SilverWp\Interfaces\Plugin $plugin_class main plugin class
         *
         * @access public
         */
        public function isPlugin( Plugin $plugin_class ) {

            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            $plugin_file = $plugin_class->getPluginName() . '/' . $plugin_class->getPluginName() . '.php';
            if ( is_plugin_active( $plugin_file ) ) {
                register_activation_hook(
                    __FILE__
                    , array(
                        $plugin_class,
                        'activateHook'
                    )
                );
                register_deactivation_hook(
                    __FILE__
                    , array(
                        $plugin_class,
                        'deactivateHook'
                    )
                );
            }
        }

        /**
         *
         * Add some extra fields class and fixes for VP
         *
         * @access private
         */
        private function vpFix() {
	        \VP_AutoLoader::remove_directories( VP_CLASSES_DIR, VP_NAMESPACE );

	        $classes = SILVERWP_LIBS_PATH . 'ssvafpress/classes/';

	        \VP_AutoLoader::add_directories( $classes, VP_NAMESPACE );
	        \VP_AutoLoader::add_directories( VP_CLASSES_DIR, VP_NAMESPACE );
	        \VP_AutoLoader::register();

	        $vp = \VP_FileSystem::instance();
	        $vp->remove_directories( 'views' );

	        $views = SILVERWP_LIBS_PATH . 'ssvafpress/views';

	        $vp->add_directories( 'views', $views );
	        $vp->add_directories( 'views', ABSPATH . 'Views' );
	        $vp->add_directories( 'views', VP_VIEWS_DIR );

	        FileSystem::getInstance()->addDirectory( 'ssvp_views', $views );
        }
    }
}