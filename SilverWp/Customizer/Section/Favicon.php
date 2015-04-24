<?php
/*
 * Copyright (C) 2014 Michal Kalkowski <michal at silversite.pl>
 *
 * SilverWp is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Favicon Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * SilverWp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Favicon Public License for more details.
 *
 * You should have received a copy of the GNU Favicon Public License
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
use SilverWp\Customizer\Control\Slider;
use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\Customizer\Section\Favicon' ) ) {

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
    class Favicon extends SectionAbstract {
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
                'title'       => Translate::translate( 'Favicon' ),
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
            //logo standard version
            $favicon = new Image( 'favicon' );
            $favicon->setLabel( Translate::translate( 'Icon' ) );
            $favicon->setDefault( '' );
            $favicon->setIsLessVariable( false );
            $favicon->setDescription( Translate::translate( 'image should have 16×16px' ) );
            $this->addControl( $favicon );

            $apple = new Image( 'favicon_apple' );
            $apple->setLabel( Translate::translate( 'Apple iPhone Icon Upload' ) );
            $apple->setDefault( '' );
            $apple->setDescription( Translate::translate( 'favicon for Apple iPhone (57px x 57px)' ) );
            $apple->setIsLessVariable( false );
            $this->addControl( $apple );

            $apple_retina = new Slider( 'favicon_apple_retina' );
            $apple_retina->setLabel( Translate::translate( 'Apple iPhone Retina Icon Upload' ) );
            $apple_retina->setDefault( 0 );
            $apple_retina->setMin( 0 );
            $apple_retina->setMax( 50 );
            $apple_retina->setStep( 1 );
            $apple_retina->setIsLessVariable( false );
            $apple_retina->setDescription( Translate::translate( 'favicon for Apple iPhone Retina Version (114px x 114px)' ) );
            $this->addControl( $apple_retina );

            //logo light version
            $light = new Image( 'logo_light' );
            $light->setLabel( Translate::translate( 'Favicon light version' ) );
            $light->setDefault( '' );
            $light->setIsLessVariable( false );
            $light->setDescription( Translate::translate( 'on brand-color background container' ) );
            $this->addControl( $light );

            $light_retina = new Image( 'logo_light_retina' );
            $light_retina->setLabel( Translate::translate( 'Retina version @2x' ) );
            $light_retina->setDefault( '' );
            $light_retina->setDescription( Translate::translate( 'on brand-color background container' ) );
            $light_retina->setIsLessVariable( false );
            $this->addControl( $light_retina );

            $light_margin_top = new Slider( 'logo_standard_margin_top' );
            $light_margin_top->setLabel( Translate::translate( 'Margin top for light version' ) );
            $light_margin_top->setDefault( 0 );
            $light_margin_top->setMin( 0 );
            $light_margin_top->setMax( 50 );
            $light_margin_top->setStep( 1 );
            $light_margin_top->setIsLessVariable( false );
            $light_margin_top->setDescription( Translate::translate( 'set the margin size in "px"' ) );
            $this->addControl( $light_margin_top );
        }
    }
}