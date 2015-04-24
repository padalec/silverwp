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
/*
 Repository path: $HeadURL: $
 Last committed: $Revision: $
 Last changed by: $Author: $
 Last changed date: $Date: $
 ID: $Id: $
*/
namespace SilverWp\Customizer;

use SilverWp\Debug;
use SilverWp\Helper\File;
use SilverWp\Less;
use SilverWp\SingletonAbstract;
use SilverWp\Customizer\Panel\PanelInterface;
use SilverWp\Customizer\Section\SectionInterface;

require_once( LIBS_PATH . '/kirki/kirki.php' );

if ( ! class_exists( '\SilverWp\Customizer' ) ) {

    /**
     * Main customizer class. Setup main settings.
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Customizer
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Customizer extends SingletonAbstract implements CustomizerInterface {

        /**
         *
         * Sections and panels class handler
         *
         * @var array
         * @access private
         */
        private $sections = array();

        /**
         * Main css script handler (added to wp_register_script)
         *
         * @var string
         * @static
         * @access public
         */
        public static $css_script_handler = 'project_style';

        /**
         *
         * All less variable handler
         *
         * @var array
         * @access private
         * @static
         */
        private static $less_variables = array();

        /**
         * Class constructor
         *
         * @access protected
         */
        protected function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, 'generatePreview' ), 150 );
            add_action( 'customize_save_after', array( $this, 'generateAfterSave' ), 151 );
            //add_action( 'customize_preview_init', array( $this, 'generatePreview' ), 11 );
            add_filter( 'kirki/config', array( $this, 'config' ) );
        }

        /**
         * Customizer main configuration
         *
         * @return array
         * @access public
         */
        public function config() {
            $args = array(
                // Change the logo image. (URL)
                // If omitted, the default theme info will be displayed.
                // A good size for the logo is 250x50.
                //'logo_image'    => ASSETS_URI . 'img/admin/logo_theme_option_panel.png',
                // The color of active menu items, help bullets etc.
                'color_active'  => 'light-grey',
                // Color used for secondary elements and disable/inactive controls
                //'color_light'   => '#8cddcd',
                // Color used for button-set controls and other elements
                'color_select'  => 'with',
                // Color used on slider controls and image selects
                'color_accent'  => '#FF5740',
                // The generic background color.
                // You should choose a dark color here as we're using white for the text color.
                //'color_back'    => '#222',
                // If Kirki is embedded in your theme, then you can use this line to specify its location.
                // This will be used to properly enqueue the necessary stylesheets and scripts.
                // If you are using kirki as a plugin then please delete this line.
                'url_path'      => SILVERWP_THEME_URL . '/lib/SilverWp/libs/kirki/',
                // If you want to take advantage of the background control's 'output',
                // then you'll have to specify the ID of your stylesheet here.
                // The "ID" of your stylesheet is its "handle" on the wp_enqueue_style() function.
                // http://codex.wordpress.org/Function_Reference/wp_enqueue_style
                'stylesheet_id' => self::$css_script_handler,
            );

            return $args;
        }

        /**
         * Add new section to customizer interface
         *
         * @param \SilverWp\Customizer\Section\SectionInterface $section
         *
         * @access public
         */
        public function addSection( $section ) {
            if ( $section instanceof SectionInterface ) {
                $this->sections[ $section->getName() ] = $section;
            } elseif ( $section instanceof PanelInterface ) {
                $this->sections[ $section->getPanelId() ] = $section;
            }
        }

        /**
         *
         * Generate temp css from less for customizer preview
         *
         * @access public
         */
        public function generatePreview() {

            if ( is_customize_preview() ) {
                try{
                    $less = Less::getInstance();
                    $less->setUploadDir( ASSETS_PATH . 'css/generated' );
                    $less->setUploadUrl( ASSETS_URI . 'css/generated' );
                    $less_variable = $this->getLessVariablesFromControls();
                    $less->setVariables( $less_variable );
                    $less->compileCss();
                } catch (\Exception $ex) {
                    echo $ex->getMessage();
                    silverwp_debug_array( $ex->getTrace(), 'Full stack:' );
                }
            }
        }

        /**
         *
         * Generate CSS file from less variable and save it
         *
         * @access public
         */
        public function generateAfterSave() {
            try {
                Less::$remove_random = true;
                $less = Less::getInstance();
                $less->setUploadDir( ASSETS_PATH . 'css' );
                $less->setUploadUrl( ASSETS_URI . 'css' );
                $less_variable = $this->getLessVariablesFromControls();
                $less->setVariables( $less_variable );
                $less->compileCss();
                $this->deleteCssTmp();
            } catch (\Exception $ex) {
                echo $ex->getMessage();
                silverwp_debug_array( $ex->getTraceAsString(), 'Stack trace:' );
            }
        }

        /**
         *
         * Reset less variable
         *
         * @static
         * @access public
         */
        public static function resetLessVariable() {
            self::$less_variables = array();
        }

        /**
         * Get all registered less variable
         *
         * @return array
         * @access public
         */
        public function getLessVariable() {
            $this->getLessVariablesFromControls();
            return self::$less_variables;
        }

        /**
         *
         * Add new less variable
         *
         * @param string $name less variable name
         * @param string $value  variable value
         *
         * @static
         * @access public
         */
        public static function addLessVariable( $name, $value ) {
            self::$less_variables[ $name ] = $value;
        }

        /**
         *
         * Get less variable from registered controls in sections
         *
         * @return array
         * @access protected
         */
        protected function getLessVariablesFromControls() {
            if ( ! count( self::$less_variables ) ) {
                foreach ( $this->sections as $section ) {
                    if ( $section instanceof PanelInterface ) {
                        $sections = $section->getSections();
                        foreach ( $sections as $section ) {
                            $controls = $section->getControls();
                            foreach ( $controls as $control ) {
                                if ( $control->getIsLessVariable() ) {
                                    self::$less_variables[ $control->getName() ] = $control->getValue();
                                }
                            }
                        }

                    } elseif ( $section instanceof SectionInterface ) {
                        $controls = $section->getControls();
                        foreach ( $controls as $control ) {
                            if ( $control->getIsLessVariable() ) {
                                self::$less_variables[ $control->getName() ] = $control->getValue();
                            }
                        }
                    }
                }
            }
            return self::$less_variables;
        }

        /**
         *
         * Delete all tmp generated CSS files
         *
         * @access private
         */
        private function deleteCssTmp() {
            $files = File::get_file_list( ASSETS_PATH . 'css/generated', array(), false, true );
            foreach( $files as $file ) {
                unlink( $file );
            }
        }
    }
}