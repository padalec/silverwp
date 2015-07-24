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
namespace SilverWp\Helper\Control;

use SilverWp\Debug;
use SilverWp\FileSystem;
use SilverWp\SilverWp;
use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\Helper\Control\SidebarPosition' ) ) {

    /**
     *
     * Control with sidebar position to choice
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Helper\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright SilverSite.pl 2015
     * @version $Revision:$
     */
    class SidebarPosition extends RadioImage {

        /**
         *
         * Class constructor
         *
         * @param string $name
         * @access public
         */
        public function __construct( $name = 'sidebar' ) {
            parent::__construct( $name );

            $images_uri = FileSystem::getDirectory( 'images_uri' );

            $sidebar_positions = array(
                array(
                    'value' => 0,
                    'label' => Translate::translate( 'None' ),
                    'img'   => $images_uri . 'admin/sidebar/icon_0_sidebar_off.png',
                ),
                array(
                    'value' => 1,
                    'label' => Translate::translate( 'Left sidebar' ),
                    'img'   => $images_uri . 'admin/sidebar/icon_1_sidebar_off.png',
                ),
                array(
                    'value' => 2,
                    'label' => Translate::translate( 'Right sidebar' ),
                    'img'   => $images_uri . 'admin/sidebar/icon_2_sidebar_off.png',
                ),
            );

            $this->setOptions( $sidebar_positions );
        }

        /**
         *
         * Check the current post or page have a sidebar
         *
         * @static
         * @access public
         * @return boolean
         */
        public static function isDisplayed() {
            //fix Ticket #220
            if ( ( is_search() && is_home() ) || is_tag() || is_date()
                 || is_archive()
            ) {
                $post_id   = \SilverWp\Helper\Option::get_option( 'page_for_posts' );
                $post_type = 'page';
            } else {
                $page_object = get_queried_object();
                $post_id     = get_queried_object_id();
                $post_type   = isset( $page_object->post_type )
                    ? $page_object->post_type : 'portfolio';
            }

            $sidebar = \SilverWp\Helper\MetaBox::isSidebar( $post_type, $post_id );

            $display = apply_filters( 'sage/display_sidebar', $sidebar );

            return $display;
        }
    }
}