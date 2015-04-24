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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ThemeOption/Menu/Layout.php $
  Last committed: $Revision: 2576 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-03-16 15:55:30 +0100 (Pn, 16 mar 2015) $
  ID: $Id: Layout.php 2576 2015-03-16 14:55:30Z padalec $
 */

namespace SilverWp\ThemeOption\Menu;

use SilverWp\Translate;

if ( ! class_exists( '\SilverWp\ThemeOption\Menu\Layout' ) ) {
    /**
     * Menu Layout
     *
     * @author Michal Kalkowski <michal at silversite.pl>
     * @version $Id: Layout.php 2576 2015-03-16 14:55:30Z padalec $
     * @category WordPress
     * @package SilverWp
     * @subpackage ThemeOption\Menu
     * @copyright (c) 2009 - 2014, SilverSite.pl
     */
    class Layout extends MenuAbstract {

        /**
         *
         * This method is used to add
         * sections and controls inside menu page.
         *
         * @return void
         * @access protected
         */
        protected function createMenu() {
            $this->setName( 'layout' );
            $this->setLabel( Translate::translate( 'Layout' ) );
            $this->setIcon( 'font-awesome:fa-home' );
            $this->addSubMenu( new ShortCodes() );
        }
    }
}