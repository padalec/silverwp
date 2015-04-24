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

if ( ! class_exists( 'SilverWp\Customizer\Section\TopBar' ) ) {

    /**
     *
     * TopBar section
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Wp\Customizer\Section
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class TopBar extends SectionAbstract {
        protected $name = 'topbar';

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
                'title'       => Translate::translate( 'Top Bar' ),
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
            $enabled = new Switcher( 'topbar_visible' );
            $enabled->setLabel( Translate::translate( 'Turn on top bar' ) );
            $enabled->setDefault( 'on' );
            $enabled->setIsLessVariable( false );
            $this->addControl( $enabled );

            $welcome_text = new Text( 'topbar_welcome_text' );
            $welcome_text->setLabel( Translate::translate( 'Welcome text' ) );
            $welcome_text->setDefault( null );
            $welcome_text->setDescription( Translate::translate( 'First sentence displayed in Top Bar' ) );
            $welcome_text->setIsLessVariable( false );
            //$welcome_text->setDependency( $enabled, 'on' );
            $this->addControl( $welcome_text );

            $show_address = new Switcher( 'topbar_show_address' );
            $show_address->setLabel( Translate::translate( 'Show company address' ) );
            $show_address->setDefault( 'off' );
            $show_address->setIsLessVariable( false );
            //$show_address->setDependency( $enabled, 'on' );
            $this->addControl( $show_address );

            $show_phone = new Switcher( 'topbar_show_phone' );
            $show_phone->setLabel( Translate::translate( 'Show company phone' ) );
            $show_phone->setDefault( 'off' );
            $show_phone->setIsLessVariable( false );
            //$show_phone->setDependency( $enabled, 'on' );
            $this->addControl( $show_phone );

            $show_mobile = new Switcher( 'topbar_show_mobile' );
            $show_mobile->setLabel( Translate::translate( 'Show mobile number' ) );
            $show_mobile->setDefault( 'off' );
            $show_mobile->setIsLessVariable( false );
            //$show_mobile->setDependency( $enabled, 'on' );
            $this->addControl( $show_mobile );

            $show_email = new Switcher( 'topbar_show_email' );
            $show_email->setLabel( Translate::translate( 'Show email address' ) );
            $show_email->setDefault( 'off' );
            $show_email->setIsLessVariable( false );
            //$show_email->setDependency( $enabled, 'on' );
            $this->addControl( $show_email );

            $show_wpml = new Switcher( 'topbar_show_wpml' );
            $show_wpml->setLabel( Translate::translate( 'Show WPML language switcher' ) );
            $show_wpml->setDefault( 'off' );
            $show_wpml->setIsLessVariable( false );
            //$show_wpml->setDependency( $enabled, 'on' );
            $this->addControl( $show_wpml );

            $show_social_icon = new Switcher( 'topbar_show_social_icon' );
            $show_social_icon->setLabel( Translate::translate( 'Show social icons' ) );
            $show_social_icon->setDefault( 'off' );
            $show_social_icon->setIsLessVariable( false );
            //$show_social_icon->setDependency( $enabled, 'on' );
            $this->addControl( $show_social_icon );

        }
    }
}