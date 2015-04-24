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
namespace SilverWp\PostRelationship;

use SilverWp\Debug;
use SilverWp\SingletonAbstract;
use SilverWp\Exception;
use SilverWp\Translate;

if ( ! class_exists( '\SilverWp\PostRelationship\Relationship' ) ) {

    /**
     *
     * Create relationship between two or more post types
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Schedule
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Relationship {

        protected $settings = array();

        /**
         * @access protected
         *
         * @param $name
         */
        public function __construct( $name ) {
            $this->setName( $name );
        }

        public function setName( $name ) {
            $this->settings[ 'name' ] = $name;

            return $this;
        }

        public function setFrom( $post_type_name ) {
            $this->settings[ 'from' ] = $post_type_name;

            return $this;
        }

        public function setTo( $post_type ) {

            if ( is_array( $post_type ) ) {
                foreach ( $post_type as $type ) {
                    $this->settings[ 'to' ][ ] = $type->getName();
                }
            } else {
                if ( SingletonAbstract::isImplemented( $post_type, 'SilverWp\PostType\PostTypeInterface' ) ) {
                    $this->settings[ 'to' ] = $post_type->getName();
                } else {
                    throw new Exception(
                        Translate::translate( 'The param $post_type isn\'t instance of \SilverWp\PostType\PostTypeInterface' )
                    );
                }
            }

            return $this;
        }

        public function setFields( array $fields ) {
            $this->settings['fields'] = $fields;
            return $this;
        }
        /**
         * When registering a connection type, you can control if and
         * where the connections metabox shows up in the admin.
         *
         * @param string $show Possible values for the 'show' param:
         *                      'any' - show admin box everywhere (default)
         *                      'from' - show the admin column only on the 'from' end
         *                      'to' - show the admin column only on the 'to' end
         *                      false - don't show admin box at all
         * @param string $context Possible values for the 'context' param:
         *                          'side' - show admin box in the right column
         *                          'advanced' - show the admin box under the main content editor
         *
         * @access public
         * @return $this
         */
        public function setAdminBox( $show, $context ) {
            $this->settings[ 'admin_box' ] = array(
                'show'    => $show,
                'context' => $context
            );
            return $this;
        }

        public function run() {
            add_action( 'p2p_init', array( $this, 'connections' ) );

        }

        public function connections() {
            if ( function_exists( 'p2p_register_connection_type' ) ) {
                p2p_register_connection_type( $this->settings );
            } else {
                throw new Exception(
                    Translate::translate(
                        'Post 2 post plugin isn\'t activate. To create post type relationship plugin post 2 post is required.'
                    )
                );
            }
        }

        public function getSettings() {
            return $this->settings;
        }
    }
}