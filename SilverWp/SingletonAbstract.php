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

namespace SilverWp;

use SilverWp\Interfaces\Singleton;

if ( ! class_exists( '\SilverWp\SingletonAbstract' ) ) {
	/**
	 *
	 * Singleton class
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       1.0
	 * @category      WordPress
	 * @package       SilverWp
	 * @copyright     2009 - 2014, (c) SilverSite.pl
	 * @abstract
	 */
	abstract class SingletonAbstract implements Singleton {
		/**
		 *
		 * @var object
		 */
		protected static $instance = array();

		/**
		 * @abstract
		 * @access protected
		 */
		abstract protected function __construct();

		/**
		 *
		 * Get class instance
		 *
		 * @static
		 * @access public
		 * @final
		 */
		final public static function getInstance() {
			$class = \get_called_class();
			if ( ! isset( static::$instance[ $class ] ) ) {
				static::$instance[ $class ] = new static();
			}

			return static::$instance[ $class ];
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
			$class                      = \get_called_class();
			static::$instance[ $class ] = null;
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

		/**
		 * Block cloning object
		 *
		 * @access private
		 * @final
		 */
		final private function __clone(){}
	}
}