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

use SilverWp\Customizer\Control\Switcher;
use SilverWp\Customizer\Control\Image;
use SilverWp\Customizer\Control\Textarea;
use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\Customizer\Section\LayoutFooter' ) ) {

    /**
     *
     * LayoutFooter section
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Wp\Customizer\Section
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class LayoutFooter extends SectionAbstract {
        protected $name = 'layout_footer';

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
                'priority'    => 6,
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
            $bg_image = new Image( 'footer_bg_image' );
            $bg_image->setLabel( Translate::translate( 'Background Image' ) );
            $bg_image->setDefault( null );
            $bg_image->setIsLessVariable( false );
            $this->addControl( $bg_image );

            $copyright_enabled = new Switcher( 'copyright_enabled' );
            $copyright_enabled->setLabel( Translate::translate( 'Copyright enabled?' ) );
            $copyright_enabled->setDefault( 'On' );
            $copyright_enabled->setIsLessVariable( false );
            $this->addControl( $copyright_enabled );

            $copyright_text = new Textarea( 'copyright_text' );
            $copyright_text->setLabel( Translate::translate( 'Copyright text' ) );
            $copyright_text->setDefault( Translate::translate( 'Copyright dynamite-studio.pl' ) );
            //$copyright_text->setDependency( $copyright_enabled, 'On' );
            $copyright_text->setIsLessVariable( false );
            $this->addControl( $copyright_text );

        }
    }
}