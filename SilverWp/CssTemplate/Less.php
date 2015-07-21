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

if ( ! class_exists( '\SilverWp\CssTemplate\Less' ) ) {

    define( 'WP_LESS_COMPILATION', 'legacy' );
    define( 'WP_LESS_COMPILER', 'less.php' );

    /**
     * Generate css file from theme options
     *
     * @category WordPress
     * @package SilverWp
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright SilverSite.pl 2015
     * @version $Revision:$
     */
    class Less extends CssTemplateAbstract {

        /**
         *
         * Remove random value from generated CSS file
         *
         * @var bool
         * @access public
         */
        public static $remove_random = false;

        /**
         *
         * Class constructor
         *
         * @access protected
         */
        protected function __construct() {
            parent::__construct();
            add_filter( 'wp-less_stylesheet_compute_target_path', array( $this, 'filterWpLessTargetPath' ), 10, 1 );
            add_filter( 'wp-less_stylesheet_save', array( $this, 'makeRelativeImagePath' ), 99 );
            add_filter( 'wp_less_compiler', array( $this, 'compiler' ) );
        }

        /**
         * Update custom.css file to defined variables
         *
         * @access public
         */
        public function generateLessCssFileAfterOptionsSave() {

            if ( isset( $_GET[ 'page' ] ) && 'silverwp-theme_options' != $_GET[ 'page' ] ) { // && ! $css_is_writable
                return;
            }

            $this->compileCss();
        }

        /**
         *
         * Parse and register less file and convert it to css
         *
         * @param string $handler
         * @param string $src      css file source
         * @param array  $deps
         * @param bool   $version
         * @param string $media
         * @param bool   $compress the file should by compressed
         *
         * @return mixed
         * @throws Exception
         * @access public
         */
        public function registerStylesheetsTemplates(
            $handler = 'custom.less',
            $src = '',
            array $deps = array(),
            $version = false,
            $media = 'all',
            $compress = false
        ) {
            if ( class_exists( '\WPLessPlugin' ) ) {
                $less = \WPLessPlugin::getInstance();
                $less->dispatch(); // we’re done, everything works as if the plugin is activated

                $less_config = $less->getConfiguration();
                $less_config->setUploadDir( $this->upload_dir );
                $less_config->setUploadUrl( $this->upload_url );

                $css_uri = FileSystem::getDirectory( 'css_uri' );
                $less->setImportDir( array( $css_uri . 'less' ) );

                $less_variable = $this->variables;
                $less->setVariables( $less_variable );

                \Less_Parser::$default_options[ 'compress' ] = $compress;
                //\Less_Parser::$default_options['cache_method'] = 'serialize';

                \WPLessStylesheet::$upload_dir = $less_config->getUploadDir();
                \WPLessStylesheet::$upload_uri = $less_config->getUploadUrl();

                if ( ! wp_style_is( $handler, 'registered' ) ) {

                    if ( ! $src ) {
                        $src = $css_uri . 'less/style.less';
                    }

                    wp_register_style( $handler, $src, $deps, $version, $media );
                    wp_enqueue_style( $handler );
                }

                return $less->processStylesheet( $handler, true );
            } else {
                throw new Exception( Translate::translate( 'WP-Less plugin not found.' ) );
            }

        }

        /**
         * Cache less files
         *
         * @param string $inputFile
         * @param string $outputFile
         *
         * @access private
         */
        private function lessCache( $inputFile, $outputFile ) {
            if ( count( self::$dynamic_stylesheets ) ) {
                // load the cache
                $cacheFile = $inputFile . '.cache';

                if ( file_exists( $cacheFile ) ) {
                    $cache = unserialize( file_get_contents( $cacheFile ) );
                } else {
                    $cache = $inputFile;
                }
                $less = new \lessc();
                // create a new cache object, and compile
                $newCache = $less->cachedCompile( $cache );

                // output a LESS file, and cache file only if it has been modified since last compile
                if ( ! is_array( $cache ) || $newCache[ 'updated' ] > $cache[ 'updated' ] ) {
                    file_put_contents( $cacheFile, serialize( $newCache ) );
                    file_put_contents( $outputFile, $newCache[ 'compiled' ] );
                }
            }
        }
        /**
         *
         * Default less compiler
         *
         * @return string
         * @access public
         */
        public function compiler() {
            return 'less.php';
        }
    }
}

