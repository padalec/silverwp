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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ThemeOption/Menu/General.php $
  Last committed: $Revision: 2575 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-03-16 10:52:36 +0100 (Pn, 16 mar 2015) $
  ID: $Id: General.php 2575 2015-03-16 09:52:36Z padalec $
 */

namespace SilverWp\ThemeOption\Menu;

use SilverWp\Translate;

if ( ! class_exists( '\SilverWp\ThemeOption\Menu\General' ) ) {

    /**
     * General
     *
     * @author Michal Kalkowski <michal at silversite.pl>
     * @version $Id: General.php 2575 2015-03-16 09:52:36Z padalec $
     * @category WordPress
     * @package SilverWp
     * @subpackage ThemeOption\Menu
     * @copyright (c) 2009 - 2014, SilverSite.pl
     */
    class General extends MenuAbstract {
        public function createMenu() {
            $this->setName( 'general' );
            $this->setLabel( Translate::translate( 'General' ) );
            $this->setIcon( 'font-awesome:fa-cogs' );

            $this->addSubMenu( new CompanyInfo() );
            $this->addSubMenu( new ContactPage() );
            $this->addSubMenu( new Social() );
            $this->addSubMenu( new CustomJs() );
            $this->addSubMenu( new ThemeUpdate() );
        }
    }
}