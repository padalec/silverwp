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
namespace SilverWp;

if ( ! class_exists( '\SilverWp\PluginAbstract' ) ) {
    /**
     *
     * Main plugin class
     *
     * @category WordPress
     * @package SilverWp
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     * @abstract
     */
    abstract class PluginAbstract implements PluginInterface {

        /**
         *
         * Plugin name
         *
         * @var string
         * @access protected
         */
        protected $plugin_name;

        /**
         *
         * Get plugin name
         *
         * @return string
         * @throws \SilverWp\Exception
         * @access public
         */
        public function getPluginName() {
            if ( ! $this->plugin_name ) {
                throw new Exception( Translate::translate( 'Class property $plugin_name is required and can\'t be empty' ) );
            }

            return $this->plugin_name;
        }

        public function addDirectory($name, $path) {
            $vp = VP_FileSystem::instance();
            $vp->add_directories( $name, $path );
        }
    }
}