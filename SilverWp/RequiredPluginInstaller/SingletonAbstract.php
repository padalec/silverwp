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

namespace RequiredPluginInstaller;

if ( ! class_exists( '\RequiredPluginInstaller\SingletonAbstract' ) ) {
    /**
     *
     * Singleton class
     *
     * @author Michal Kalkowski <michal at silversite.pl>
     * @version $Id: SingletonAbstract.php 2182 2015-01-21 12:00:49Z padalec $
     * @category WordPress
     * @package RequiredPluginInstaller
     * @copyright (c) 2009 - 2014, SilverSite.pl
     * @todo add magic __get and __set method
     * @abstract
     */
    abstract class SingletonAbstract implements SingletonInterface {
        /**
         *
         * @var object
         */
        private static $instance = array();

        /**
         * @abstract
         * @access protected
         */
        abstract protected function __construct();

        /**
         *
         * Get class instance
         *
         * @return object
         * @static
         * @access public
         * @final
         */
        final public static function getInstance() {
            $class = \get_called_class();
            if ( ! isset( self::$instance[ $class ] ) ) {
                self::$instance[ $class ] = new $class();
            }

            return self::$instance[ $class ];
        }

        /**
         *
         * Reset instance
         *
         * @static
         * @access public
         * @final
         */
        final public static function resetInstance() {
            $class                    = \get_called_class();
            self::$instance[ $class ] = null;
        }

        /**
         *
         * Check the class ($class) implements interface ($interface)
         *
         * @param string $class
         * @param string $interface
         *
         * @return bool
         * @access public
         * @static
         */
        public static function isImplemented( $class, $interface ) {
            $interfaces = class_implements( $class );
            $implements = in_array( $interface, $interfaces );

            return $implements;
        }
    }
}