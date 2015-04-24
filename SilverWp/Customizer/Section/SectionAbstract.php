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
namespace SilverWp\Customizer\Section;

use SilverWp\Translate;
use SilverWp\Customizer\Control\ControlInterface;
use SilverWp\Customizer\Section\Exception;

if ( ! class_exists( 'SilverWp\Customizer\Section\SectionAbstract' ) ) {

    /**
     * Base section class
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Wp\Customizer\Sections
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     * @abstract
     */
    abstract class SectionAbstract implements SectionInterface {
        /**
         *
         * Section name
         *
         * @var string
         * @access protected
         */
        protected $name;

        /**
         *
         * Unique panel id
         *
         * @var string
         * @access private
         */
        private $panel_id;

        /**
         * All section controls
         *
         * @var array
         * @access private
         */
        private $controls = array();

        /**
         *
         * @var array
         * @access private
         */
        private $controls_settings = array();

        /**
         *
         * Class constructor register section elements
         *
         * @access public
         */
        public function __construct() {
            add_action( 'customize_register', array( $this, 'init' ), 2 );
            add_filter( 'kirki/controls', array( $this, 'registerControls' ), 1 );
        }

        /**
         * Get all section controls
         *
         * @param array $controls
         *
         * @return array
         * @access public
         */
        public function registerControls( array $controls ) {
            $this->controls_settings = $controls;
            $this->createControls();
            return $this->controls_settings;
        }

        /**
         * Initialize customizer and add panel and section object
         *
         * @param \WP_Customize_Manager $wp_customize
         *
         * @access public
         */
        public function init( \WP_Customize_Manager $wp_customize ) {
            $this->addSection( $wp_customize );
        }

        /**
         * Set unique panel id
         *
         * @param string $panel_id
         *
         * @return $this
         * @access public
         */
        public function setPanelId( $panel_id ) {
            $this->panel_id = $panel_id;

            return $this;
        }

        /**
         * Add new section element
         *
         * @param \WP_Customize_Manager $wp_customize class object instants
         *
         * @access private
         */
        private function addSection( \WP_Customize_Manager $wp_customize ) {
            $params = $this->getSectionParams();
            if ( isset( $this->panel_id ) ) {
                $params[ 'panel' ] = $this->panel_id;
            }
            /* TODO implements auto priority settings
            if ( ! in_array( 'priority', $params ) ) {
                $sections = $wp_customize->sections();
                foreach($sections as $section) {
                    silverwp_debug_array($section->priority);
                }
                //end();
            }*/
            $wp_customize->add_section( $this->name, $params );
        }

        /**
         * Add section arguments. An associative array containing arguments for the control.
         * array('title' => '', 'priority' => '', 'description' => '')
         *
         * @return array
         * @access protected
         * @link http://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_section
         * @abstract
         */
        protected abstract function getSectionParams();

        /**
         *
         * List of all sections controls fields
         *
         * @return array
         * @access public
         * @abstract
         */
        protected abstract function createControls();

        /**
         *
         * Get section name
         *
         * @return string
         * @access public
         */
        public function getName() {
            return $this->name;
        }

        /**
         *
         * Add control field to section
         *
         * @param \SilverWp\Customizer\Control\ControlInterface $control
         *
         * @access public
         */
        public function addControl( ControlInterface $control ) {
            $control->setSectionName( $this->getName() );
            $settings = $control->getSettings();
            $this->controls[ $control->getName() ] = $control;
            $this->controls_settings[] = $settings;

            if ( ! in_array( 'priority', $settings ) ) {
                end( $this->controls_settings );
                $last_key = key( $this->controls_settings );
                $control->setPriority( $last_key );
                reset( $this->controls_settings );
            }
        }

        /**
         *
         * Flip array from array( value => label) to array (label => value)
         *
         * @param array $data
         *
         * @param bool  $empty add empty element to beginning of array
         *
         * @return array
         * @access public
         * @static
         */
        public static function flipSourceData( array $data, $empty = false ) {
            $return = array();

            if ( $empty ) {
                $return[ '' ] = '';
            }

            foreach ( $data as $value ) {
                if ( isset( $value[ 'img' ] ) ) {
                    $return[ $value[ 'value' ] ] = $value[ 'img' ];
                } else {
                    $return[ $value[ 'value' ] ] = $value[ 'label' ];
                }
            }

            return $return;
        }

        /**
         *
         * Get all controls for current section
         *
         * @return array
         * @access public
         */
        public function getControls() {
            return $this->controls;
        }
    }
}