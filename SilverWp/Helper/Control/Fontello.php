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
namespace SilverWp\Helper\Control;

use SilverWp\Debug;
use SilverWp\FileSystem;
use SilverWp\Helper\MetaBox;

if ( ! class_exists( '\SilverWp\Helper\Control\Fontello' ) ) {

    /**
     *
     * Fontello Icon Chooser
     * This control will provide a single select box with complete list of
     * <a href="http://fontello.com/">Fontello icons</a>,
     * with icon preview on every icon item.
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Helper\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Fontello extends MultiControlAbstract implements EnqueueAssetsInterface {
        protected $type = 'fontico';

        /**
         *
         * Class constructor
         *
         * @param string $name
         *
         * @throws \SilverWp\Helper\Control\Exception
         */
        public function __construct( $name ) {
            parent::__construct( $name );
            //add fontello icons
            $css_uri = FileSystem::getDirectory( 'fonts_uri' ) . 'fontello.css';
            $items = MetaBox::getFontelloIcons( 'icon', $css_uri, 'silverwp_fontello_icons' );
            $this->setOptions( $items );
        }

        /**
         *
         * Add additional css or js files
         *
         * @return void
         * @access public
         */
        public function enqueueAssets() {
	        $fonts_uri = FileSystem::getDirectory( 'fonts_uri' );
            wp_register_style( 'fontello_icons', $fonts_uri . 'fontello.css' );
            wp_enqueue_style( 'fontello_icons' );
        }

        /**
         * The default value of the chooser,
         * refers to an item choice value or smart tags: {{first}} / {{last}}.
         *
         * @param string $default
         *
         * @return $this
         * @access public
         */
        public function setDefault( $default ) {
            parent::setDefault( array( $default ) );

            return $this;
        }
    }
}