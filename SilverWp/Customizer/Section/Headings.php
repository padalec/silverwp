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

if ( ! class_exists( 'SilverWp\Customizer\Section\Headings' ) ) {

    /**
     *
     * Heading fonts configuration section
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Wp\Customizer\Section
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Headings extends SectionAbstract {
        protected $name = 'headings';

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
                'title'       => Translate::translate( 'Headings' ),
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
            $family = new Gwf( 'headings-font-family' );
            $family->setLabel( Translate::translate( 'Google-Font font family' ) );
            $family->setDefault( '"Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif' );
            $this->addControl( $family );

            $sizeH1 = new Slider( 'font-size-h1' );
            $sizeH1->setLabel( Translate::translate( 'Size H1' ) );
            $sizeH1->setMin( 1 );
            $sizeH1->setMax( 70 );
            $sizeH1->setStep( 1 );
            $sizeH1->setDefault( 60 );
            $this->addControl( $sizeH1 );

            $line_heightH1 = new Text( 'line-height-h1' );
            $line_heightH1->setLabel( Translate::translate( 'Line-height H1' ) );
            $line_heightH1->setDefault( 1.066666667 );
            $this->addControl( $line_heightH1 );

            $sizeH2 = new Slider( 'font-size-h2' );
            $sizeH2->setLabel( Translate::translate( 'Size H2' ) );
            $sizeH2->setMin( 1 );
            $sizeH2->setMax( 70 );
            $sizeH2->setStep( 1 );
            $sizeH2->setDefault( 30 );
            $this->addControl( $sizeH2 );

            $line_heightH2 = new Text( 'line-height-h2' );
            $line_heightH2->setLabel( Translate::translate( 'Line-height H2' ) );
            $line_heightH2->setDefault( 1.166666667 );
            $this->addControl( $line_heightH2 );

            $sizeH3 = new Slider( 'font-size-h3' );
            $sizeH3->setLabel( Translate::translate( 'Size H3' ) );
            $sizeH3->setMin( 1 );
            $sizeH3->setMax( 70 );
            $sizeH3->setStep( 1 );
            $sizeH3->setDefault( 30 );
            $this->addControl( $sizeH3 );

            $line_heightH3 = new Text( 'line-height-h3' );
            $line_heightH3->setLabel( Translate::translate( 'Line-height H3' ) );
            $line_heightH3->setDefault( 1.208333333 );
            $this->addControl( $line_heightH3 );

            $sizeH4 = new Slider( 'font-size-h4' );
            $sizeH4->setLabel( Translate::translate( 'Size H4' ) );
            $sizeH4->setMin( 1 );
            $sizeH4->setMax( 70 );
            $sizeH4->setStep( 1 );
            $sizeH4->setDefault( 18 );
            $this->addControl( $sizeH4 );

            $line_heightH4 = new Text( 'line-height-h4' );
            $line_heightH4->setLabel( Translate::translate( 'Line-height H4' ) );
            $line_heightH4->setDefault( 1.222222222 );
            $this->addControl( $line_heightH4 );


            $sizeH5 = new Slider( 'font-size-h5' );
            $sizeH5->setLabel( Translate::translate( 'Size H5' ) );
            $sizeH5->setMin( 1 );
            $sizeH5->setMax( 70 );
            $sizeH5->setStep( 1 );
            $sizeH5->setDefault( 14 );
            $this->addControl( $sizeH5 );

            $line_heightH5 = new Text( 'line-height-h5' );
            $line_heightH5->setLabel( Translate::translate( 'Line-height H5' ) );
            $line_heightH5->setDefault( 1.428571429 );
            $this->addControl( $line_heightH5 );

            $sizeH6 = new Slider( 'font-size-h6' );
            $sizeH6->setLabel( Translate::translate( 'Size H6' ) );
            $sizeH6->setMin( 1 );
            $sizeH6->setMax( 70 );
            $sizeH6->setStep( 1 );
            $sizeH6->setDefault( 12 );
            $this->addControl( $sizeH6 );

            $line_heightH6 = new Text( 'line-height-h6' );
            $line_heightH6->setLabel( Translate::translate( 'Line-height H6' ) );
            $line_heightH6->setDefault( 1.333333333 );
            $this->addControl( $line_heightH6 );

            $weight = new GwfWeight( 'headings-font-weight' );
            $weight->setLabel( Translate::translate( 'Weight' ) );
            $weight->setDefault( 800 );
            $this->addControl( $weight );

            $style = new GwfStyle( 'headings-font-style' );
            $style->setLabel( Translate::translate( 'Google-Font style' ) );
            $style->setDefault( 'normal' );
            $this->addControl( $style );

            $transform = new GwfTransform( 'headings-font-transform' );
            $transform->setLabel( Translate::translate( 'Google-Font Transform' ) );
            $transform->setDefault( 'uppercase' );
            $this->addControl( $transform );

            $subset = new GwfSubset( 'subset' );
            $subset->setLabel( Translate::translate( 'Google-Font Subset' ) );
            $subset->setDefault( 'latin' );
            $this->addControl( $subset );
        }
    }
}