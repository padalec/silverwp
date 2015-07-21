<?php

/*
 * Copyright (C) 2014 Michal Kalkowski <michal at silversite.pl>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace SilverWp\CssTemplate;

if ( ! interface_exists( 'SilverWp\CssTemplate\CssTemplateInterface' ) ) {
    /**
     * Interface CssTemplateInterface
     *
     * @package SilverWp\CssTemplate
     * @category WordPress
     * @package SilverWp
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright SilverSite.pl 2015
     * @version $Revision:$
     */
    interface CssTemplateInterface {

        /**
         * Compile variables to css
         *
         * @return void
         * @access public
         */
        public function registerFallback();

        /**
         * Set upload director where compiled css files will be saved
         *
         * @param string $upload_dir
         *
         * @return $this
         * @access public
         */
        public function setUploadDir( $upload_dir );

        /**
         * Set up upload url where compiled css files will be saved
         *
         * @param string $upload_url
         *
         * @return $this
         * @access public
         */
        public function setUploadUrl( $upload_url );

        /**
         * Add new variable
         *
         * @param string $name  variable name
         * @param string $value variable value
         *
         * @return $this
         */
        public function addVariable( $name, $value );

        /**
         *
         * Setup css variables
         *
         * @param array $variables associative array with key => value
         *
         * @return $this
         */
        public function setVariables( array $variables );

        /**
         * Return array with all stylesheets templates
         *
         * @return array
         * @access public
         */
        public function getStylesheetsTemplates();

        /**
         *
         * Add stylesheet template file
         *
         * @param string $handle template handler name
         * @param array  $params array( 'src' => '', 'deps' => array(), 'version' => false, 'media' => 'all' )
         *
         * @access public
         * @static
         */
        public static function addStylesheetsTemplate( $handle, array $params );
    }
}