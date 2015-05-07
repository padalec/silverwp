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
namespace SilverWp\Customizer\Panel;

use SilverWp\Customizer\Section\SectionInterface;
use SilverWp\Debug;

if ( ! class_exists( 'SilverWp\Customizer\Panel\PanelAbstract' ) ) {

    /**
     * Base customizer panel class
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Wp\Customizer\Panel
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     * @abstract
     */
    abstract class PanelAbstract implements PanelInterface {

        /**
         * Unique panel id
         *
         * @var string
         * @access protected
         */
        protected $panel_id;

        /**
         *
         * Section handler
         *
         * @var array
         * @access private
         */
        private $sections = array();

        /**
         *
         * Class constructor register customizer
         *
         * @access public
         */
        public function __construct() {
            add_action( 'customize_register', array( $this, 'init' ) );
        }

        /**
         *
         * Initialize panel
         *
         * @param \WP_Customize_Manager $wp_customize
         *
         * @throws \SilverWp\Customizer\Panel\Exception
         * @access public
         */
        public function init( \WP_Customize_Manager $wp_customize ) {
            $this->addPanel( $wp_customize );
            $this->createSections();
        }

        /**
         *
         * Add new panel to customizer
         *
         * @param \WP_Customize_Manager $wp_customize
         *
         * @throws \SilverWp\Customizer\Panel\Exception
         * @access private
         */
        private function addPanel( \WP_Customize_Manager $wp_customize ) {
            if ( ! isset( $this->panel_id ) ) {
                throw new Exception(
                    Translate::translate( 'If You want add panel to your section first define panel_id class property.' )
                );
            }
            $params = $this->createPanelParams();
            Debug::dump($params);
            $wp_customize->add_panel( $this->panel_id, $params );
        }

        /**
         *
         * Create sections elements add to panel
         *
         * @access protected
         * @abstract
         */
        protected abstract function createSections();

        /**
         *
         * An associative array with panel params:
         * array(
         *      'priority'       => 10,
         *      'capability'     => 'edit_theme_options',
         *      'theme_supports' => '',
         *      'title'          => __('Theme Options', 'mytheme'),
         *      'description'    => __('Several settings pertaining my theme', 'mytheme'),
         * )
         *
         * @return array
         * @access protected
         * @abstract
         */
        protected abstract function createPanelParams();

        /**
         *
         * Add section to panel container
         *
         * @param \SilverWp\Customizer\Section\SectionInterface $section
         *
         * @access public
         */
        public function addSection( SectionInterface $section ) {
            $section->setPanelId( $this->getPanelId() );
            $this->sections[] = $section;
        }

        /**
         *
         * Get unique panel id
         *
         * @return string
         * @access public
         */
        public function getPanelId() {
            return $this->panel_id;
        }

        /**
         * Get all registered sections
         *
         * @return array
         * @access public
         */
        public function getSections() {
            return $this->sections;
        }
    }
}