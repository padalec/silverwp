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
use SilverWp\Customizer\Control\Switcher;
use SilverWp\Customizer\Control\Text;
use SilverWp\Customizer\Control\Toggle;
use SilverWp\ShortCode\Form\Element\Checkbox;
use SilverWp\Translate;
use SilverWp\Customizer\Control\GroupTitle;

if ( ! class_exists( 'SilverWp\Customizer\Section\LayoutHeader' ) ) {

    /**
     *
     * LayoutHeader section
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Wp\Customizer\Section
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class LayoutHeader extends SectionAbstract {
        protected $name = 'layout_header';

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
                'priority'    => 4,
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
            $search_icon = new Switcher( 'header_search_icon' );
            $search_icon->setLabel( Translate::translate( 'Search Icon' ) );
            $search_icon->setDefault( 'on' );
            $search_icon->setIsLessVariable( false );
            $this->addControl( $search_icon );

            $show_wpml = new Switcher( 'header_wpml_flags' );
            $show_wpml->setLabel( Translate::translate( 'WPML languages Flags' ) );
            $show_wpml->setDefault( 'off' );
            $show_wpml->setIsLessVariable( false );
            //$show_wpml->setDependency( $enabled, 'on' );
            $this->addControl( $show_wpml );

        }
    }
}