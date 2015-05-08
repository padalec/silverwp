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

if ( ! class_exists( '\SilverWp\Less' ) ) {

    define( 'WP_LESS_COMPILATION', 'legacy' );
    define( 'WP_LESS_COMPILER', 'less.php' );

    /**
     * Generate css file from theme options
     *
     * @category WordPress
     * @package SilverWp
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Less extends SingletonAbstract {

        /**
         * @var string
         */
        private $upload_dir;

        /**
         * @var string
         */
        private $upload_url;

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
         * @var null|array
         * @static
         * @access private
         */
        private static $dynamic_stylesheets = null;

        /**
         *
         * @var array
         * @access private
         */
        private $less_variables = array();

        /**
         *
         * Class constructor
         *
         * @access protected
         */
        protected function __construct() {
            //add_action( 'plugins_loaded', array( $this, 'registerLessFallback' ) );
            add_filter( 'wp-less_stylesheet_compute_target_path', array( $this, 'filterWpLessTargetPath' ), 10, 1 );
            add_filter( 'wp-less_stylesheet_save', array( $this, 'makeRelativeImagePath' ), 99 );
            add_filter( 'wp_less_compiler', array( $this, 'lessCompiler' ) );
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
         * Get less files and compile variable to CSS.
         *
         * @access public
         */
        public function compileCss() {

            $dynamic_stylesheets = $this->getDynamicCssList();
            foreach ( $dynamic_stylesheets as $stylesheet_handle => $stylesheet ) {
                $this->generateLessCssFiles( $stylesheet_handle, $stylesheet[ 'src' ], $stylesheet[ 'compress' ] );
                //$this->lessCache( $stylesheet[ 'path' ], $stylesheet['output_path'] );
            }
        }

        /**
         *
         * Set place when generated files will be stored
         *
         * @param string $upload_dir full path
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
         * @param string $upload_url
         *
         * @return $this
         * @access public
         */
        public function setUploadUrl( $upload_url ) {
            $this->upload_url = $upload_url;

            return $this;
        }

        /**
         *
         * Parse and register less file and convert it to css
         *
         * @param string $handler
         * @param string $src css file source
         * @param bool   $compress the file should by compressed
         *
         * @return mixed
         * @throws \SilverWp\Exception
         * @access public
         */
        public function generateLessCssFiles( $handler = 'custom.less', $src = '', $compress = false ) {
            if ( class_exists( '\WPLessPlugin' ) ) {
                $less = \WPLessPlugin::getInstance();
                $less->dispatch(); // we’re done, everything works as if the plugin is activated

                $less_config = $less->getConfiguration();
                $less_config->setUploadDir( $this->upload_dir );
                $less_config->setUploadUrl( $this->upload_url );
                $assets_uri = FileSystem::getDirectory( 'assets_uri' );

                $less->setImportDir( array( $assets_uri . 'less' ) );

                $less_variable = $this->less_variables;
                $less->setVariables( $less_variable );

                \Less_Parser::$default_options[ 'compress' ] = $compress;
                //\Less_Parser::$default_options['cache_method'] = 'serialize';

                \WPLessStylesheet::$upload_dir = $less_config->getUploadDir();
                \WPLessStylesheet::$upload_uri = $less_config->getUploadUrl();

                if ( ! wp_style_is( $handler, 'registered' ) ) {

                    if ( ! $src ) {
                        $src = $assets_uri . 'less/style.less';
                    }

                    wp_register_style( $handler, $src );
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
         * Add dynamic css files
         *
         * @param string $handle
         * @param array  $params
         *
         * @access public
         * @static
         */
        public static function addDynamicCss( $handle, array $params ) {
            self::$dynamic_stylesheets[ $handle ] = $params;
        }

        /**
         *
         * Get list off all dynamic css files loaded
         *
         * @return array|null
         * @access public
         */
        public function getDynamicCssList() {

            if ( null === self::$dynamic_stylesheets ) {

                $assets_uri  = FileSystem::getDirectory( 'assets_uri' );
                $assets_path = FileSystem::getDirectory( 'assets_path' );

                $theme_version = SILVERWP_VER;

                self::$dynamic_stylesheets[ 'app.less' ] = array(
                    'path'         => $assets_path . 'less/app.less',
                    'output_path'  => $assets_path . 'less/generated/app.css',
                    'src'          => $assets_uri . 'less/app.less',
                    'fallback_src' => $assets_uri . 'css/generated/app.css',
                    'deps'         => array(),
                    'ver'          => $theme_version,
                    'media'        => 'all',
                    'compress'     => false,
                );

                self::$dynamic_stylesheets[ 'style.less' ] = array(
                    'path'         => $assets_path . 'less/style.less',
                    'output_path'  => $assets_path . 'less/generated/style.css',
                    'src'          => $assets_uri . 'less/style.less',
                    'fallback_src' => $assets_uri . 'css/generated/style.css',
                    'deps'         => array( 'project_style' ),
                    'ver'          => $theme_version,
                    'media'        => 'all',
                    'compress'     => false,
                );
                /*
                self::$dynamic_stylesheets[ 'ds-custom.less' ] = array(
                    'path'         => $template_directory . 'less/test1.less',
                    'src'          => $template_uri . 'less/test1.less',
                    //'fallback_src' => $template_uri . 'css/compiled/test1-%preset%.css',
                    'deps'         => array(),
                    'ver'          => $theme_version,
                    'media'        => 'all',
                    'compress'     => true,
                );

                if ( dt_is_woocommerce_enabled() ) {

                    $dynamic_stylesheets['wc-dt-custom.less'] = array(
                        'path' => $template_directory . '/css/wc-dt-custom.less',
                        'src' => $template_uri . '/css/wc-dt-custom.less',
                        'fallback_src' => $template_uri . '/css/compiled/wc-dt-custom-%preset%.css',
                        'deps' => array(),
                        'ver' => $theme_version,
                        'media' => 'all'
                    );
                }
                */
            }

            return self::$dynamic_stylesheets;
        }

        /**
         * Add new php variable will be change in less file
         *
         * @param string $name variable name
         * @param string $value variable value
         *
         * @access public
         */
        public function addVariable( $name, $value ) {
            $this->less_variables[ $name ] = $value;
        }

        /**
         * Set php to less variables
         *
         * @param array $variables
         *
         * @return $this
         * @access public
         */
        public function setVariables( array $variables ) {
            $this->less_variables = $variables;

            return $this;
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
        public function filterWpLessTargetPath( $target_path ) {
            $target_path_array = explode( '/', $target_path );
            $css_file          = end( $target_path_array );
            if ( self::$remove_random ) {
                $css_file = str_replace( '-%s', '', $css_file );
            }

            return '/' . $css_file;
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

            $content = str_replace( set_url_scheme( content_url(), 'http' ), '../../../..', $content );
            $content = str_replace( set_url_scheme( content_url(), 'https' ), '../../../..', $content );

            return $content;
        }

        /**
         *
         * Default less compiler
         *
         * @return string
         * @access public
         */
        public function lessCompiler() {
            return 'less.php';
        }
    }
}

