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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ThemeOption/Menu/MenuInterface.php $
  Last committed: $Revision: 2568 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-03-13 15:28:41 +0100 (Pt, 13 mar 2015) $
  ID: $Id: MenuInterface.php 2568 2015-03-13 14:28:41Z padalec $
 */

namespace SilverWp\ThemeOption\Menu;

/**
 * Theme Options Menu Interface
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: MenuInterface.php 2568 2015-03-13 14:28:41Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage ThemeOption\Menu
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
interface MenuInterface
{
    /**
     *
     * menu method all fields should by based hear
     *
     * @access public
     * @return array
     */
    public function getSettings();
}
