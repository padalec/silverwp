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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/PostInterface.php $
  Last committed: $Revision: 2182 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:00:49 +0100 (Śr, 21 sty 2015) $
  ID: $Id: PostInterface.php 2182 2015-01-21 12:00:49Z padalec $
 */

namespace SilverWp;

/**
 * Post Interface
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: PostInterface.php 2182 2015-01-21 12:00:49Z padalec $
 * @category WordPress
 * @package SilverWp
 * @copyright (c) 2009 - 2014, SilverSite.pl 
 */
interface PostInterface
{
    /**
     * set post id
     *
     * @param int $post_id post id
     * @access public
     * @return
     */
    public function setPostId($post_id);
    /**
     * get post id
     *
     * @access public
     * @return integer
     */
    public function getPostId();
}
