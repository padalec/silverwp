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

/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ThemeOption/Menu/MenuAbstract.php $
  Last committed: $Revision: 2568 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-03-13 15:28:41 +0100 (Pt, 13 mar 2015) $
  ID: $Id: MenuAbstract.php 2568 2015-03-13 14:28:41Z padalec $
 */

namespace SilverWp\ThemeOption\Menu;

use SilverWp\Helper\Control\ControlInterface;
use SilverWp\Helper\Form\ThemeOption;
use SilverWp\ThemeOption\Exception;
use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\ThemeOption\Menu\MenuAbstract' ) ) {

    /**
     *
     * Theme Options Menu Abstract
     * base class for create menu in theme options
     *
     * @author Michal Kalkowski <michal at silversite.pl>
     * @version $Id: MenuAbstract.php 2568 2015-03-13 14:28:41Z padalec $
     * @category WordPress
     * @package SilverWp
     * @subpackage ThemeOption
     * @copyright (c) 2009 - 2014, SilverSite.pl
     */
    abstract class MenuAbstract implements MenuInterface {

        /**
         *
         * Array with all menu settings
         *
         * @var array
         * @access private
         */
        private $settings = array();

        /**
         *
         * Class constructor
         *
         * @access public
         */
        public function __construct() {
            $this->createMenu();
        }

        /**
         *
         * This method is used to add
         * sections and controls inside menu page.
         *
         * @return void
         * @access protected
         */
        protected abstract function createMenu();

        /**
         *
         * Set menu name
         *
         * @param string $name
         *
         * @return $this
         * @access public
         */
        public function setName( $name ) {
            $this->settings[ 'name' ] = $name;

            return $this;
        }

        /**
         * Set title alias to setLabel()
         *
         * @param string $title
         *
         * @return $this
         * @access public
         */
        public function setTitle( $title ) {
            $this->setLabel( $title );

            return $this;
        }

        /**
         *
         * Set label
         *
         * @param string $label
         *
         * @return $this
         * @access public
         */
        public function setLabel( $label ) {
            $this->settings[ 'title' ] = $label;

            return $this;
        }

        /**
         * Set font awesome icon
         *
         * @param string $icon
         *
         * @return $this
         * @access public
         */
        public function setIcon( $icon ) {
            $this->settings[ 'icon' ] = $icon;

            return $this;
        }

        /**
         * Add control or section to menu page
         *
         * @param  $control
         *
         * @return $this
         * @throws \SilverWp\ThemeOption\Exception
         * @access
         */
        public function addControl( $control ) {
            if ( $control instanceof ControlInterface || $control instanceof SectionInterface ) {
                $this->settings[ 'controls' ][ ] = $control->getSettings();
            } else {
                throw new Exception(
                    Translate::params(
                        'Argument passed to %s::addControl() isn\'t instance of SilverWp\Helper\Control\ControlInterface or SilverWp\ThemeOption\Menu\SectionInterface',
                        __CLASS__
                    )
                );
            }

            return $this;
        }

        /**
         *
         * Add sub menu to menu
         *
         * @param \SilverWp\ThemeOption\Menu\MenuAbstract $menu
         *
         * @return $this
         * @access public
         */
        public function addSubMenu( MenuAbstract $menu ) {
            $this->settings[ 'menus' ][ ] = $menu->getSettings();

            return $this;
        }

        /**
         *
         * Get menu settings
         *
         * @return array
         * @access public
         */
        public function getSettings() {
            return $this->settings;
        }
    }
}