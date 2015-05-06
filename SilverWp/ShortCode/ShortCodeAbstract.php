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
namespace SilverWp\ShortCode;

use SilverWp\FileSystem;
use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\ShortCode\ShortCodeAbstract' ) ) {
    /**
     * Base Short Code class
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage SilverWp\ShortCode
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     * @abstract
     */
    abstract class ShortCodeAbstract implements ShortCodeInterface {
        /**
         * Settings form tag_base
         *
         * @var string
         * @access protected
         */
        protected $tag_base = null;

        /**
         * add close tag to short code tag
         *
         * @var boolean
         * @access protected
         */
        protected $with_close_tag = true;

        /**
         * Class constructor
         *
         * @throws Exception
         * @access protected
         */
        public function __construct() {
            if ( \is_null( $this->tag_base ) ) {
                throw new Exception( Translate::translate( 'Property $tag_base is required and can\'t be empty' ) );
            }
            $this->register();
        }

        /**
         *
         * Return element tag_base
         *
         * @return string
         * @access public
         */
        public function getTagBase() {
            return $this->tag_base;
        }

        /**
         *
         * Get short code tag
         *
         * @return string
         * @access public
         */
        public function getTag() {
            $short_code = '[' . $this->tag_base . ']';
            if ( $this->with_close_tag ) {
                $short_code .= '[/' . $this->tag_base . ']';
            }

            return $short_code;
        }

        /**
         * Render short code view
         *
         * @param array       $data data passed too view
         *
         * @param null|string $view_file
         *
         * @return string
         * @access protected
         */
        protected function render( array $data, $view_file = null ) {
            if ( \is_null( $view_file ) ) {
                $view_file = $this->tag_base;
            }
            try {
                $view = View::getInstance()->load( 'shortcode/' . $view_file, $data );

                return $view;
            } catch ( Exception $ex ) {
                echo $ex->displayAdminNotice();
            }
        }

        /**
         * Register short code
         *
         * @access private
         * @return void
         */
        private function register() {
            \add_shortcode( $this->tag_base, array( $this, 'content' ) );
        }

        /**
         *
         * Get assets uri
         *
         * @return string
         * @access protected
         */
        protected function getAssetsUri() {
            $file_system = FileSystem::getInstance();
            $assets_uri = $file_system->getDirectories( 'assets_uri' );

            return $assets_uri;
        }
    }
}