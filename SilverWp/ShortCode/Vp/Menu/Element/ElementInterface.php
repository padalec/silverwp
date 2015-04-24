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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/plugins/schedule/vendor/SilverWp/ShortCode/Vp/Menu/Element/ElementInterface.php $
  Last committed: $Revision: 2591 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-03-24 17:26:17 +0100 (Wt, 24 mar 2015) $
  ID: $Id: ElementInterface.php 2591 2015-03-24 16:26:17Z padalec $
 */

namespace SilverWp\ShortCode\Vp\Menu\Element;

/**
 * Short Code Menu Element Interface
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: ElementInterface.php 2591 2015-03-24 16:26:17Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage ShortCode\Generator\Menu\Element
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
interface ElementInterface {

    /**
     *
     * Get short code element name
     *
     * @return string element name
     * @access public
     */
    public function getName();

    /**
     *
     * Get element settings
     *
     * @return array
     * @access public
     */
    public function getSettings();

    /**
     *
     * Set element title
     *
     * @param string $title
     *
     * @return $this
     * @access public
     */
    public function setTitle( $title );
}
