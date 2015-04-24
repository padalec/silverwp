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

use SilverWp\Customizer\Control\GwfStyle;
use SilverWp\Customizer\Control\GwfSubset;
use SilverWp\Customizer\Control\GwfTransform;
use SilverWp\Customizer\Control\GwfWeight;
use SilverWp\Customizer\Control\Text;
use SilverWp\Customizer\Control\Gwf;
use SilverWp\Customizer\Control\Slider;
use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\Customizer\Section\Menu' ) ) {

    /**
     *
     * Main navigation menu section
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Wp\Customizer\Section
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Menu extends SectionAbstract {
        protected $name = 'menu';

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
                'title'       => Translate::translate( 'Main navigation menu' ),
                'priority'    => 5,
                //'description' => Translate::translate( 'All theme texts except headings and menu' )
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
            $family = new Gwf( 'navbar-font-family' );
            $family->setLabel( Translate::translate( 'Google-Font font family' ) );
            $family->setDefault( '"Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif' );
            $this->addControl( $family );

            $weight = new GwfWeight( 'navbar-font-weight' );
            $weight->setLabel( Translate::translate( 'Weight' ) );
            $weight->setDefault( 800 );
            $this->addControl( $weight );

            /*
            $style = new GwfStyle( 'font-family-style' );
            $style->setLabel( Translate::translate( 'Style' ) );
            $style->setDefault( 'normal' );
            $this->addControl( $style );
            */
            $subset = new GwfSubset( 'subset' );
            $subset->setLabel( Translate::translate( 'Google Font Subsets' ) );
            $subset->setDescription( Translate::translate( 'The subsets used from Google\'s API.' ) );
            $subset->setDefault( 'latin' );
            $this->addControl( $subset );

            $transform = new GwfTransform( 'navbar-font-transform' );
            $transform->setLabel( Translate::translate( 'Transform' ) );
            $transform->setDefault( 'none' );
            $this->addControl( $transform );

            $size = new Slider( 'navbar-font-size' );
            $size->setLabel( Translate::translate( 'Size' ) );
            $size->setMin( 1 );
            $size->setMax( 70 );
            $size->setStep( 1 );
            $size->setDefault( 12 );
            $this->addControl( $size );

            $line_height = new Text( 'line-height-base' );
            $line_height->setLabel( Translate::translate( 'Line-height' ) );
            $line_height->setDefault( 1.333333333 );
            $this->addControl( $line_height );

        }
    }
}