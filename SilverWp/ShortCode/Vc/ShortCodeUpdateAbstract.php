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
namespace SilverWp\ShortCode\Vc;

use SilverWp\Debug;

if ( ! class_exists( '\SilverWp\ShortCode\Vc\ShortCodeUpdateAbstract' ) ) {
    /**
     * Change visual composer short code
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage SilverWp\ShortCode\Vc
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    abstract class ShortCodeUpdateAbstract extends ShortCodeAbstract {
        /**
         * Short code existing attributes to remove from setting form
         *
         * @var array
         * @access protected
         */
        protected $remove_attribute = array();

        /**
         * Class constructor
         *
         * @throws \SilverWp\ShortCode\Exception
         */
        public function __construct() {
            if ( ! isset( $this->tag_base ) && empty( $this->tag_base ) ) {
                throw new Exception( Translate::translate( 'Class variable $tag_base is required and can\'t be empty.' ) );
            }
            if ( isset( $this->remove_attribute ) && count( $this->remove_attribute ) ) {
                $this->removeAttribute( $this->remove_attribute );
            }
            if ( method_exists( $this, 'create' ) ) {
                $this->addAttributes();
            }
        }

        /**
         * Remove attributes
         *
         * @param array $attributes
         *
         * @access private
         */
        private function removeAttribute( array $attributes ) {
            foreach ( $attributes as $attribute ) {
                vc_remove_param( $this->tag_base, $attribute );
            }
        }

        /**
         * Add new attributes to setting form of existing short code
         *
         * @access private
         */
        private function addAttributes() {
            $this->create();
            $controls = $this->getControls();
            vc_add_params( $this->tag_base, $controls );
        }

        /**
         * Render Short Code content Only overwrite from interface
         *
         * @param array  $args short code attributes
         * @param string $content content string added between short code tags
         *
         * @return mixed
         * @access public
         */
        public function content( $args, $content ) {
            // just overwrite from interface
        }
    }
}