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

use SilverWp\Customizer\Control\Color;
use SilverWp\Translate;
use SilverWp\Customizer\Control\GroupTitle;

if ( ! class_exists( 'SilverWp\Customizer\Section\Footer' ) ) {

    /**
     *
     * Footer section
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Wp\Customizer\Section
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Footer extends SectionAbstract {
        protected $name = 'footer';

        /**
         * Add section arguments. An associative array containing arguments for the control.
         * array('title' => '', 'priority' => '', 'description' => '')
         *
         * @return array
         * @access protected
         * @link http://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_section
         */
        protected function getSectionParams() {
            $params = array(
                'title'       => Translate::translate( 'Footer' ),
                'priority'    => 3,
                'description' => ''
            );
            return $params;
        }

        /**
         *
         * List of all sections controls fields
         *
         * @return array
         * @access protected
         */
        protected function createControls() {
            //footer
            $bg_color = new Color( 'footer-bg' );
            $bg_color->setLabel( Translate::translate( 'Background color' ) );
            $bg_color->setDefault( '#272727' );
            $this->addControl( $bg_color );

            $heading_color = new Color( 'footer-heading-color' );
            $heading_color->setLabel( Translate::translate( 'Widget heading color' ) );
            $heading_color->setDefault( '#ffffff' );
            $this->addControl( $heading_color );

            $text_color = new Color( 'footer-color' );
            $text_color->setLabel( Translate::translate( 'Text and link color' ) );
            $text_color->setDefault( '#868686' );
            $this->addControl( $text_color );

            $link_color = new Color( 'footer-link-color' );
            $link_color->setLabel( Translate::translate( 'Link color' ) );
            $link_color->setDefault( '#ffffff' );
            $this->addControl( $link_color );

            $link_hover_color = new Color( 'footer-link-hover-color' );
            $link_hover_color->setLabel( Translate::translate( 'Link hover color' ) );
            $link_hover_color->setDefault( '#2edddf' );
            $this->addControl( $link_hover_color );
        }
    }
}