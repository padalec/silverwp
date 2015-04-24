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

if ( ! class_exists( 'SilverWp\Customizer\Section\Header' ) ) {

    /**
     *
     * Header section
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Wp\Customizer\Section
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Header extends SectionAbstract {
        protected $name = 'header';

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
                'title'       => Translate::translate( 'Header' ),
                'priority'    => 2,
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
            //topbar
            $title = new GroupTitle( 'top_bar' );
            $title->setLabel( Translate::translate( 'Top bar' ) );
            $title->setPriority( 1 );
            $this->addControl( $title );

            $topbar_bg_color = new Color( 'topbar-default-bg' );
            $topbar_bg_color->setLabel( Translate::translate( 'Background color' ) );
            $topbar_bg_color->setPriority( 2 );
            $topbar_bg_color->setDefault( '#afafaf' );
            $this->addControl( $topbar_bg_color );

            $topbar_text_color = new Color( 'topbar-default-color' );
            $topbar_text_color->setLabel( Translate::translate( 'Text color' ) );
            $topbar_text_color->setPriority( 3 );
            $topbar_text_color->setDefault( '#afafaf' );
            $this->addControl( $topbar_text_color );

            //navbar
            $title = new GroupTitle( 'navbar' );
            $title->setLabel( Translate::translate( 'Nav bar' ) );
            $title->setPriority( 4 );
            $this->addControl( $title );

            $bg_color = new Color( 'navbar-default-bg' );
            $bg_color->setLabel( Translate::translate( 'Background color' ) );
            $bg_color->setPriority( 5 );
            $bg_color->setDefault( '#ffffff' );
            $this->addControl( $bg_color );

            $link_color = new Color( 'navbar-default-link-color' );
            $link_color->setLabel( Translate::translate( 'Navigation menu link hover color' ) );
            $link_color->setPriority( 6 );
            $link_color->setDefault( '#2edddf' );
            $this->addControl( $link_color );

            $dropdown_bg_color = new Color( 'dropdown-bg' );
            $dropdown_bg_color->setLabel( Translate::translate( 'Navigation drop-down menu background color' ) );
            $dropdown_bg_color->setPriority( 7 );
            $dropdown_bg_color->setDefault( '#e8e8e8' );
            $this->addControl( $dropdown_bg_color );

            $dropdown_link_color = new Color( 'dropdown-link-color' );
            $dropdown_link_color->setLabel( Translate::translate( 'Navigation drop-down menu link color' ) );
            $dropdown_link_color->setPriority( 8 );
            $dropdown_link_color->setDefault( '#868686' );
            $this->addControl( $dropdown_link_color );

            $dropdown_link_hover_color = new Color( 'dropdown-link-hover-color' );
            $dropdown_link_hover_color->setLabel( Translate::translate( 'Navigation dropdown menu link hover color' ) );
            $dropdown_link_hover_color->setPriority( 9 );
            $dropdown_link_hover_color->setDefault( '#2edddf' );
            $this->addControl( $dropdown_link_hover_color );

            //page header
            $title = new GroupTitle( 'page_header' );
            $title->setLabel( Translate::translate( 'Page header' ) );
            $title->setPriority( 10 );
            $this->addControl( $title );

            $bg_color = new Color( 'page-header-bg' );
            $bg_color->setLabel( Translate::translate( 'Background color' ) );
            $bg_color->setPriority( 11 );
            $bg_color->setDefault( '#2edddf' );
            $this->addControl( $bg_color );

            $text_color = new Color( 'page-header-color' );
            $text_color->setLabel( Translate::translate( 'Text and link color' ) );
            $text_color->setPriority( 12 );
            $text_color->setDefault( '#ffffff' );
            $this->addControl( $text_color );

        }
    }
}