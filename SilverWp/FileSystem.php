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
namespace SilverWp;

if ( ! class_exists( '\SilverWp\FileSystem' ) ) {

    /**
     * File system class
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage SilverWp
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class FileSystem extends SingletonAbstract {
        private $lookup_dirs = array();

        /**
         * @access protected
         */
        protected function __construct() {
        }

        /**
         * Normalize path
         *
         * @param string $path path to file
         *
         * @return string
         * @access public
         */
        public function normalizePath( $path ) {

            $path = rtrim($path, '/') . '/';

            return $path;
        }

        /**
         * Add directory to future use
         *
         * @param  string $key
         * @param  string $directory
         *
         * @return $this
         */
        public function addDirectory( $key, $directory ) {
            $this->addVpDirectory( $key, $directory );
            $this->lookup_dirs[ $key ] = $this->normalizePath($directory);

            return $this;
        }

        /**
         * Add directories to the VP autoloader, loading process will be run in orderly fashion
         * of directory addition.
         *
         * @param string       $key
         * @param string|array $directories
         *
         * @access
         */
        protected function addVpDirectory( $key, $directories ) {
            if ( class_exists( '\VP_FileSystem' ) ) {
                $vp = \VP_FileSystem::instance();
                $vp->add_directories( $key, $directories );
            }
        }

        /**
         * Remove directory.
         *
         * @param  string $key
         *
         * @access public
         */
        public function __unset( $key ) {
            unset( $this->lookup_dirs[ $key ] );
        }

        /**
         * Get all or single directories
         *
         * @param null|string $key
         *
         * @return array
         * @throws \SilverWp\Exception
         * @access public
         */
        public function getDirectories( $key = null ) {

            if ( is_null( $key ) ) {
                return $this->lookup_dirs;
            }
            if ( ! isset( $this->lookup_dirs[ $key ] ) ) {
                throw new Exception( Translate::translate( 'Key %s does not exists in registered directories', $key ) );
            }

            return $this->lookup_dirs[ $key ];
        }

        /**
         * Register data source functions
         *
         * @access public
         */
        public function registerDataSource() {
            $data_dir = $this->getDirectories( 'data' );

            $files = glob( $data_dir . DIRECTORY_SEPARATOR . '*.php' );
            if ( $files ) {
                foreach ( $files as $data_source ) {
                    include_once( $data_source );
                }
            }
        }
    }
}