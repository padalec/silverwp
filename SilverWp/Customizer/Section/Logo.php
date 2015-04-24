<?php
/*
 * Copyright (C) 2014 Michal Kalkowski <michal at silversite.pl>
 *
 * SilverWp is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Logo Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * SilverWp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Logo Public License for more details.
 *
 * You should have received a copy of the GNU Logo Public License
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

use SilverWp\Customizer\Control\Image;
use SilverWp\Customizer\Control\Switcher;
use SilverWp\Customizer\Control\Text;
use SilverWp\Customizer\Control\Slider;
use SilverWp\Helper\Option;
use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\Customizer\Section\Logo' ) ) {

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
    class Logo extends SectionAbstract {
        protected $name = 'logo';

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
                'title'       => Translate::translate( 'Logo' ),
                'priority'    => 1,
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
            //text on logo
            $text = new Text( 'text' );
            $text->setLabel( Translate::translate( 'Text' ) );
            $text->setIsLessVariable( false );
            $text->setDefault( Option::get_theme_option( 'company_name' ) );
            $this->addControl( $text );
            // add or not link to logo
            $link = new Switcher( 'logo_link' );
            $link->setIsLessVariable( false );
            $link->setLabel( Translate::translate( 'Logo link' ) );
            $link->setDefault( 'on' );
            $this->addControl( $link );

            //logo standard version
            $standard = new Image( 'logo_standard' );
            $standard->setLabel( Translate::translate( 'Logo standard version' ) );
            $standard->setDefault( '' );
            $standard->setDescription( Translate::translate( 'on white background' ) );
            $standard->setIsLessVariable( false );
            $this->addControl( $standard );

            $standard_retina = new Image( 'logo_standard_retina' );
            $standard_retina->setLabel( Translate::translate( 'Logo retina version @2x' ) );
            $standard_retina->setDefault( '' );
            $standard_retina->setDescription( Translate::translate( 'on white background' ) );
            $standard_retina->setIsLessVariable( false );
            $this->addControl( $standard_retina );

            $standard_margin_top = new Slider( 'logo_standard_margin_top' );
            $standard_margin_top->setLabel( Translate::translate( 'Logo margin top for standard version' ) );
            $standard_margin_top->setDefault( 0 );
            $standard_margin_top->setMin( 0 );
            $standard_margin_top->setMax( 50 );
            $standard_margin_top->setStep( 1 );
            $standard_margin_top->setIsLessVariable( false );
            $standard_margin_top->setDescription( Translate::translate( 'set the margin size in "px"' ) );
            $this->addControl( $standard_margin_top );

            //logo light version
            $light = new Image( 'logo_light' );
            $light->setLabel( Translate::translate( 'Logo light version' ) );
            $light->setDefault( null );
            $light->setDescription( Translate::translate( 'on brand-color background container' ) );
            $light->setIsLessVariable( false );
            $this->addControl( $light );

            $light_retina = new Image( 'logo_light_retina' );
            $light_retina->setLabel( Translate::translate( 'Logo light retina version @2x' ) );
            $light_retina->setDefault( null );
            $light_retina->setDescription( Translate::translate( 'on brand-color background container' ) );
            $light_retina->setIsLessVariable( false );
            $this->addControl( $light_retina );

            $light_margin_top = new Slider( 'logo_light_margin_top' );
            $light_margin_top->setLabel( Translate::translate( 'Logo margin top for logo light version' ) );
            $light_margin_top->setDefault( 0 );
            $light_margin_top->setMin( 0 );
            $light_margin_top->setMax( 50 );
            $light_margin_top->setStep( 1 );
            $light_margin_top->setDescription( Translate::translate( 'set the margin size in "px"' ) );
            $light_margin_top->setIsLessVariable( false );
            $this->addControl( $light_margin_top );
        }
    }
}