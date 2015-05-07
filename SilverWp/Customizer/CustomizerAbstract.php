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
namespace SilverWp\Customizer;

use SilverWp\CoreInterface;
use SilverWp\Helper\File;
use SilverWp\Less;
use SilverWp\SingletonAbstract;
use SilverWp\Customizer\Panel\PanelInterface;
use SilverWp\Customizer\Section\SectionInterface;
use SilverWp\Translate;

if ( ! class_exists( '\SilverWp\Customizer\CustomizerAbstract' ) ) {

    /**
     * Main customizer class. Setup main settings.
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Customizer
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     * @abstract
     */
    abstract class CustomizerAbstract extends SingletonAbstract implements CustomizerInterface, CoreInterface {
        /**
         * Kirki string translation
         *
         * @var array
         * @access protected
         */
        protected $strings = array();

        /**
         *
         * Change the logo image (URL).
         * If omitted, the default theme info will be displayed.
         * You may want to use a relatively large image (for example 700px wide)
         * so that it’s properly displayed on retina screens as well.
         *
         * @var string
         * @access protected
         */
        protected $logo_image;

        /**
         * Changes the theme description. Will be visible when clicking on the theme logo.
         *
         * @var string
         * @access protected
         */
        protected $description;

        /**
         * If Kirki is embedded in your theme, then you can use this line to specify its location.
         * This will be used to properly enqueue the necessary stylesheets and scripts.
         * If you are using kirki as a plugin then please do not use this line unless you know what you’re doing.
         *
         * @var string
         * @access protected
         */
        protected $url_path;

        /**
         * The accent color. This will be used on selected items and control details.
         *
         * @var string
         * @access protected
         */
        protected $color_accent = '#00bcd4';

        /**
         * @var string
         */
        protected $color_back = '#455a64';
        /**
         *
         * Sections and panels class handler
         *
         * @var array
         * @access private
         */
        private $sections = array();

        /**
         * If you want you can specify a stylesheet ID here.
         * Kirki will then enqueue its own styles using that hook.
         * If you don’t specify a stylesheet ID then kirki will automatically add a dummy file to compensate.
         * The “ID” of your stylesheet is its “handle” on the wp_enqueue_style() function.
         * See http://codex.wordpress.org/Function_Reference/wp_enqueue_style for more details.
         *
         * @var string
         * @static
         * @access public
         */
        public static $stylesheet_id = 'project_style';

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
            $this->initStrings();
            add_action( 'wp_enqueue_scripts', array( $this, 'generatePreview' ), 150 );
            add_action( 'customize_save_after', array( $this, 'generateAfterSave' ), 151 );
            //add_action( 'customize_preview_init', array( $this, 'generatePreview' ), 11 );
            add_filter( 'kirki/config', array( $this, 'init' ) );
        }

        /**
         * If you need to include Kirki in your theme,
         * then you may want to consider adding the translations here
         * using your textdomain.
         *
         * If you're using Kirki as a plugin then you can remove these.
         *
         * @access protected
         */
        protected function initStrings() {
            $this->strings = array(
                'background-color'      => Translate::translate( 'Background Color' ),
                'background-image'      => Translate::translate( 'Background Image' ),
                'no-repeat'             => Translate::translate( 'No Repeat' ),
                'repeat-all'            => Translate::translate( 'Repeat All' ),
                'repeat-x'              => Translate::translate( 'Repeat Horizontally' ),
                'repeat-y'              => Translate::translate( 'Repeat Vertically' ),
                'inherit'               => Translate::translate( 'Inherit' ),
                'background-repeat'     => Translate::translate( 'Background Repeat' ),
                'cover'                 => Translate::translate( 'Cover' ),
                'contain'               => Translate::translate( 'Contain' ),
                'background-size'       => Translate::translate( 'Background Size' ),
                'fixed'                 => Translate::translate( 'Fixed' ),
                'scroll'                => Translate::translate( 'Scroll' ),
                'background-attachment' => Translate::translate( 'Background Attachment' ),
                'left-top'              => Translate::translate( 'Left Top' ),
                'left-center'           => Translate::translate( 'Left Center' ),
                'left-bottom'           => Translate::translate( 'Left Bottom' ),
                'right-top'             => Translate::translate( 'Right Top' ),
                'right-center'          => Translate::translate( 'Right Center' ),
                'right-bottom'          => Translate::translate( 'Right Bottom' ),
                'center-top'            => Translate::translate( 'Center Top' ),
                'center-center'         => Translate::translate( 'Center Center' ),
                'center-bottom'         => Translate::translate( 'Center Bottom' ),
                'background-position'   => Translate::translate( 'Background Position' ),
                'background-opacity'    => Translate::translate( 'Background Opacity' ),
                'ON'                    => Translate::translate( 'ON' ),
                'OFF'                   => Translate::translate( 'OFF' ),
                'all'                   => Translate::translate( 'All' ),
                'cyrillic'              => Translate::translate( 'Cyrillic' ),
                'cyrillic-ext'          => Translate::translate( 'Cyrillic Extended' ),
                'devanagari'            => Translate::translate( 'Devanagari' ),
                'greek'                 => Translate::translate( 'Greek' ),
                'greek-ext'             => Translate::translate( 'Greek Extended' ),
                'khmer'                 => Translate::translate( 'Khmer' ),
                'latin'                 => Translate::translate( 'Latin' ),
                'latin-ext'             => Translate::translate( 'Latin Extended' ),
                'vietnamese'            => Translate::translate( 'Vietnamese' ),
                'serif'                 => Translate::x( 'Serif', 'font style' ),
                'sans-serif'            => Translate::x( 'Sans Serif', 'font style' ),
                'monospace'             => Translate::x( 'Monospace', 'font style' ),
            );
        }

        /**
         * Init configuration
         *
         * @return array
         * @access public
         * @link http://kirki.org/#configuration
         */
        public function init() {
            $config = array(
                'logo_image'    => $this->logo_image,
                'description'   => $this->description,
                'url_path'      => $this->url,
                'color_accent'  => $this->color_accent,
                'color_back'    => $this->color_back,
                'textdomain'    => Translate::$text_domain,
                'stylesheet_id' => self::$stylesheet_id,
                'i18n'          => $this->strings,
            );

            return $config;
        }

        /**
         * If Kirki is embedded in your theme, then you can use this line to specify its location.
         * This will be used to properly enqueue the necessary stylesheets and scripts.
         * If you are using kirki as a plugin then please do not use this line unless you know what you’re doing.
         *
         * @param string $path
         *
         * @return $this
         * @access public
         */
        public function setUrlPath($path) {
            $this->url_path = $path;
            return $this;
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
                try {
                    $less = Less::getInstance();
                    $less->setUploadDir( ASSETS_PATH . 'css/generated' );
                    $less->setUploadUrl( ASSETS_URI . 'css/generated' );
                    $less_variable = $this->getLessVariablesFromControls();
                    $less->setVariables( $less_variable );
                    $less->compileCss();
                } catch ( Exception $ex ) {
                    $ex->catchException();
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
                $less                = Less::getInstance();
                $less->setUploadDir( ASSETS_PATH . 'css' );
                $less->setUploadUrl( ASSETS_URI . 'css' );
                $less_variable = $this->getLessVariablesFromControls();
                $less->setVariables( $less_variable );
                $less->compileCss();
                $this->deleteCssTmp();
            } catch ( Exception $ex ) {
                $ex->catchException();
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
         * @param string $value variable value
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
            foreach ( $files as $file ) {
                unlink( $file );
            }
        }
    }
}