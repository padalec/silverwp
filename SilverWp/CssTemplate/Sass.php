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
namespace SilverWp\CssTemplate;

use SilverWp\Exception;
use SilverWp\FileSystem;
use SilverWp\Translate;

if ( ! class_exists( '\SilverWp\CssTemplate\Sass' ) ) {

    define('WP_SCSS_COMPILATION', 'always');
    define('WP_SCSS_ALWAYS_RECOMPILE', true);

    /**
     * Generate css file from Sass css templates files
     *
     * @category  WordPress
     * @package   SilverWp
     * @author    Michal Kalkowski <michal at silversite.pl>
     * @copyright SilverSite.pl 2015
     * @version   $Revision:$
     */
    class Sass extends CssTemplateAbstract implements CssTemplateCompilerInterface {

        /**
         * Class constructor
         *
         * @access protected
         */
        protected function __construct() {
            parent::__construct();
            add_filter( 'wp-scss_stylesheet_compute_target_path', array( $this, 'filterTargetPath' ), 10, 1 );
            add_filter( 'wp-scss_stylesheet_save', array( $this, 'makeRelativeImagePath' ), 99 );
        }

        /**
         * Register Stylesheets templates files
         *
         * @param string      $handle   handle name
         * @param string      $src      path to stylesheets template file
         * @param array       $dep      An array of registered style handles this stylesheet depends on. Default empty array.
         * @param string|bool $ver      String specifying the stylesheet version number. Used to ensure that the correct version
         *                              is sent to the client regardless of caching. Default 'false'. Accepts 'false', 'null', or 'string'.
         * @param string      $media    Optional. The media for which this stylesheet has been defined.
         *                              Default 'all'. Accepts 'all', 'aural', 'braille', 'handheld', 'projection', 'print',
         *                              'screen', 'tty', or 'tv'.
         * @param bool|false  $compress compress the target file
         *
         * @return WPScssStylesheet
         * @throws Exception
         */
        protected function registerStylesheetsTemplates(
            $handle
            , $src
            , array $dep = array()
            , $ver = false
            , $media = 'all'
            , $compress = false
        ) {
            if ( class_exists( '\WPScssPlugin' ) ) {
                $WPScssPlugin = \WPScssPlugin::getInstance();
                $WPScssPlugin->dispatch();

                $this->configDirs( $WPScssPlugin );

                $variable = $this->variables;
                $WPScssPlugin->setVariables( $variable );

                if ( ! wp_style_is( $handle, 'registered' ) ) {

                    wp_register_style( $handle, $src, $dep, $ver, $media );
                    wp_enqueue_style( $handle );
                }

                return $WPScssPlugin->processStylesheet( $handle, true );
            } else {
                throw new Exception( Translate::translate( 'Plugin WPScssPlugin not found!' ) );
            }
        }

        /**
         * Configure directories
         *
         * @param \WPScssPlugin $WPScssPlugin
         * @access private
         */
        private function configDirs( \WPScssPlugin $WPScssPlugin ) {

            $scss_config = $WPScssPlugin->getConfiguration();

            if ( isset( $this->upload_dir )
                 && isset( $this->upload_url )
            ) {
                $scss_config->setUploadDir( $this->upload_dir );
                $scss_config->setUploadUrl( $this->upload_url );
            }

            $css_template_path = FileSystem::getDirectory( 'css_template_path' );
            $WPScssPlugin->setImportDir( $css_template_path );

            \WPScssStylesheet::$upload_dir = $scss_config->getUploadDir();
            \WPScssStylesheet::$upload_uri = $scss_config->getUploadUrl();
        }

        public function compiler() {
            return 'scssphp';
        }
    }
}

