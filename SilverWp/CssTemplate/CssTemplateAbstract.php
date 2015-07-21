<?php

/*
 * Copyright (C) 2014 Michal Kalkowski <michal at silversite.pl>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace SilverWp\CssTemplate;

use SilverWp\SingletonAbstract;

if ( ! class_exists( 'SilverWp\CssTemplate\CssTemplateAbstract' ) ) {
    /**
     * Base Class for css compilers
     *
     * @abstract
     * @category   WordPress
     * @package    SilverWp
     * @subpackage CssTemplate
     * @author     Michal Kalkowski <michal at silversite.pl>
     * @copyright  SilverSite.pl 2015
     * @version    $Revision:$
     */
    abstract class CssTemplateAbstract extends SingletonAbstract
        implements CssTemplateInterface {

        /**
         *
         * @var string
         * @access protected
         */
        protected $upload_dir;

        /**
         *
         * @var string
         * @access protected
         */
        protected $upload_url;

        /**
         * Associative array with Less or Sass variables
         *
         * @var array
         * @access protected
         */
        protected $variables = array();

        /**
         *
         * @var null|array
         * @static
         * @access private
         */
        protected static $stylesheets_templates = null;

        /**
         *
         * Class constructor
         *
         * @access protected
         */
        protected function __construct() {
            add_action( 'plugins_loaded', array( $this, 'registerFallback' ) );

            $childClass = get_called_class();
            if ( $this->isImplemented( $childClass,
                '\SilverWp\CssTemplate\CssTemplateCompilerInterface' )
            ) {
                add_filter( 'wp_scss_compiler', 'compiler' );
            }
        }


        /**
         * Add new php variable will be change by Sass or Less compiler
         *
         * @param string $name  variable name
         * @param string $value variable value
         *
         * @access public
         * @return $this
         */
        public function addVariable( $name, $value ) {
            $this->variables[ $name ] = $value;

            return $this;
        }


        /**
         *
         * Set place when generated files will be stored
         *
         * @param string $upload_dir full path to upload directory
         *
         * @return $this
         * @access public
         */
        public function setUploadDir( $upload_dir ) {
            $this->upload_dir = $upload_dir;

            return $this;
        }

        /**
         *
         * Set URL to place when generated files will be stored
         * Used for enqueue scripts
         *
         * @param string $upload_url full url to upload directory
         *
         * @return $this
         * @access public
         */
        public function setUploadUrl( $upload_url ) {
            $this->upload_url = $upload_url;

            return $this;
        }

        /**
         * Set php to Sass variables
         *
         * @param array $variables
         *
         * @return $this
         * @access public
         */
        public function setVariables( array $variables ) {
            $this->variables = $variables;

            return $this;
        }

        /**
         *
         * Get less files and compile variable to CSS.
         *
         * @access public
         */
        public function registerFallback() {

            $stylesheets = $this->getStylesheetsTemplates();
            foreach ( $stylesheets as $handle => $stylesheet ) {
                $this->registerStylesheetsTemplates(
                    $handle
                    , $stylesheet['src']
                    , $stylesheet['deps']
                    , $stylesheet['ver']
                    , $stylesheet['media']
                );
            }
        }

        /**
         * Register Stylesheets templates files
         *
         * @param string      $handle   handle name
         * @param string      $src      path to stylesheets template file
         * @param array       $deps     An array of registered style handles this stylesheet depends on. Default empty array.
         * @param string|bool $ver      String specifying the stylesheet version number. Used to ensure that the correct version
         *                              is sent to the client regardless of caching. Default 'false'. Accepts 'false', 'null', or 'string'.
         * @param string      $media    Optional. The media for which this stylesheet has been defined.
         *                              Default 'all'. Accepts 'all', 'aural', 'braille', 'handheld', 'projection', 'print',
         *                              'screen', 'tty', or 'tv'.
         *
         * @abstract
         * @return WPScssStylesheet
         */
        abstract protected function registerStylesheetsTemplates(
            $handle
            , $src
            , array $deps = array()
            , $ver = false
            , $media = 'all'
        );

        /**
         * Return array with all stylesheets templates
         *
         * @return array
         * @access public
         */
        public function getStylesheetsTemplates() {
            return self::$stylesheets_templates;
        }

        /**
         *
         * Add stylesheet template file
         *
         * @param string $handle
         * @param array  $params array( 'src' => '', 'deps' => array(), 'version' => '', 'media' => 'all' )
         *
         * @access public
         * @static
         */
        public static function addStylesheetsTemplate( $handle, array $params
        ) {
            self::$stylesheets_templates[ $handle ] = $params;
        }

        /**
         *
         * Change compiled css target path.
         * Remove not necessary folder path structure
         *
         * @param string $target_path current target path
         *
         * @return string
         * @access public
         */
        public function filterTargetPath( $target_path ) {
            $target_path_array = explode( DIRECTORY_SEPARATOR, $target_path );
            $css_file          = end( $target_path_array );

            $css_file = str_replace( '-%s', '', $css_file );
            $css_file = preg_replace( '/\.css{1,}/', '', $css_file ) . '.css';

            return DIRECTORY_SEPARATOR . $css_file;
        }

        /**
         * Change absolute image path to relative
         *
         * @param string $content
         *
         * @return mixed|string
         * @access public
         */
        public function makeRelativeImagePath( $content = '' ) {

            /*if ( !get_option( 'presscore_less_css_is_writable' ) ) {
                return $content;
            }*/

            $content = str_replace( set_url_scheme( content_url(), 'http' ),
                '../../../..', $content );
            $content = str_replace( set_url_scheme( content_url(), 'https' ),
                '../../../..', $content );

            return $content;
        }
    }
}