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

if ( ! interface_exists( '\RequiredPluginInstaller\SingletonInterface' ) ) {
    /**
     * Singleton Interface
     *
     * @author Michal Kalkowski <michal at silversite.pl>
     * @version $Id: SingletonInterface.php 2182 2015-01-21 12:00:49Z padalec $
     * @category WordPress
     * @package RequiredPluginInstaller
     * @copyright (c) 2009 - 2014, SilverSite.pl
     */

    interface SingletonInterface
    {
        /**
         * Get class instance
         *
         * @return object class instance
         * @static
         * @access public
         */
        public static function getInstance();

        /**
         *
         * Reset class instance
         *
         * @static
         * @access public
         */
        public static function resetInstance();
        //public function __get();
        //public function __set();
    }
}