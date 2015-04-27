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

use SilverWp\PluginAbstract;
use SilverWp\RequiredPlugins;
use VP_FileSystem;

if ( ! class_exists( 'SilverWp\Plugin' ) ) {

    /**
     *
     * This class defines all code necessary to run during the plugin's.
     *
     * @category WordPress
     * @package SilverWp
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Plugin extends PluginAbstract {
        protected $plugin_name = 'silverwp';

        public function __construct() {
            $this->addDirectory( 'views', SILVERWP_DIR . 'views' );
        }

        /**
         *
         * All code fire when plugin is activate
         *
         * @access public
         * @static
         */
        public function activateHook() {
            //todo implement this method
        }

        /**
         *
         * All code fire when plugin is deactivate
         *
         * @access public
         * @static
         */
        public function deactivateHook() {
            //delete_option( THEME_OPTION_PREFIX );
        }
    }
}