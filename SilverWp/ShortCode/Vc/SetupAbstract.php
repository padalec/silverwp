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
 Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ShortCode/ShortCodes.php $
 Last committed: $Revision: 2308 $
 Last changed by: $Author: padalec $
 Last changed date: $Date: 2015-02-02 14:35:21 +0100 (Pn, 02 lut 2015) $
 ID: $Id: ShortCodes.php 2308 2015-02-02 13:35:21Z padalec $
*/
namespace SilverWp\ShortCode\Vc;

use SilverWp\SingletonAbstract;

if ( ! class_exists( '\SilverWp\ShortCode\Vc\SetupAbstract' ) ) {

    /**
     * Run Short Codes
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage ShortCode\Vc\Setup
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Id: ShortCodes.php 2308 2015-02-02 13:35:21Z padalec $
     */
    abstract class SetupAbstract extends SingletonAbstract {

        /**
         * Array with array ( short_code_bas => array( element_name ) )
         *
         * @var array
         * @access pubic
         * @static
         */
        public static $remove_form_element = array();

        /**
         * Un register short code
         *
         * @var array
         * @access public
         * @static
         */
        public static $un_register = array();

        /**
         * Change short code settings
         *
         * @var array
         * @access public
         * @static
         */
        public static $update_settings = array();

        /**
         * Class constructor
         *
         * @access protected
         */
        protected function __construct() {
            $this->register();
            $this->update();
            $this->removeFormElements();
            $this->unRegister();
        }

        /**
         * Register short codes
         *
         * @access protected
         */
        protected abstract function register();

        /**
         * Remove setting form elements
         *
         * @access private
         */
        private function removeFormElements() {
            foreach ( self::$remove_form_element as $short_code => $elements ) {
                foreach ( $elements as $element ) {
                    vc_remove_param( $short_code, $element );
                }
            }
        }

        /**
         * Remove short codes
         *
         * @access private
         */
        private function unRegister() {
            foreach ( self::$un_register as $short_code ) {
                vc_remove_element( $short_code );
            }
        }

        /**
         * Change VC short code settings
         *
         * @access private
         */
        private function update() {
            foreach ( self::$update_settings as $short_code => $settings ) {
                vc_map_update( $short_code, $settings );
            }
        }
    }

} 