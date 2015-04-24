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

if ( ! class_exists( 'SilverWp\Customizer\Section\General' ) ) {

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
    class General extends SectionAbstract {
        protected $name = 'general';

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
                'title'       => Translate::translate( 'General' ),
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
            $brand_primary = new Color( 'brand-primary' );
            $brand_primary->setLabel( Translate::translate( 'Brand primary' ) );
            $brand_primary->setDefault( '#2edddf' );
            $brand_primary->setPriority( 2 );
            $this->addControl( $brand_primary );

            $body_bg = new Color( 'body-bg' );
            $body_bg->setLabel( Translate::translate( 'Body background' ) );
            $body_bg->setDefault( '#ffffff' );
            $body_bg->setPriority( 3 );
            $this->addControl( $body_bg );

            $body_text = new Color( 'text-color' );
            $body_text->setLabel( Translate::translate( 'Body text' ) );
            $body_text->setDefault( '#868686' );
            $body_text->setPriority( 3 );
            $this->addControl( $body_text );

            $link_text = new Color( 'link-color' );
            $link_text->setLabel( Translate::translate( 'Link color' ) );
            $link_text->setDefault( '#868686' );
            $link_text->setPriority( 3 );
            $this->addControl( $link_text );

            $link_hover = new Color( 'link-hover-color' );
            $link_hover->setLabel( Translate::translate( 'Link hover color' ) );
            $link_hover->setDefault( '#868686' );
            $link_hover->setPriority( 3 );
            $this->addControl( $link_hover );

            $blog_body_bg = new Color( 'blog-body-bg' );
            $blog_body_bg->setLabel( Translate::translate( 'Blog template background color' ) );
            $blog_body_bg->setDefault( '#2edddf' );
            $blog_body_bg->setPriority( 3 );
            $this->addControl( $blog_body_bg );
        }
    }
}