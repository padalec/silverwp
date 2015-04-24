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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Paginator/PaginatorInterface.php $
  Last committed: $Revision: 2310 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-02-02 16:02:04 +0100 (Pn, 02 lut 2015) $
  ID: $Id: PaginatorInterface.php 2310 2015-02-02 15:02:04Z padalec $
 */

namespace SilverWp\Helper\Paginator;

/**
 * Paginator Interface
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: PaginatorInterface.php 2310 2015-02-02 15:02:04Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
interface PaginatorInterface {
    public function setTotalPosts( $total_page );

    public function setMaxNumPages( $current_page );

    public function getLinks();

    public function getTotalPosts();
}
