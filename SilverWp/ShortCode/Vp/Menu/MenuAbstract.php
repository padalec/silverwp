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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ShortCode/Generator/Menu/MenuAbstract.php $
  Last committed: $Revision: 1854 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2014-11-29 17:03:14 +0100 (So, 29 lis 2014) $
  ID: $Id: MenuAbstract.php 1854 2014-11-29 16:03:14Z padalec $
 */

namespace SilverWp\ShortCode\Vp\Menu;

use SilverWp\ShortCode\Vp\Menu\Element\ElementInterface;

/**
 * Short Code generator Template Abstract
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: MenuAbstract.php 1854 2014-11-29 16:03:14Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage SilverWp\ShortCode
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
abstract class MenuAbstract implements MenuInterface {

    private $title;

    /**
     *
     * List of all added elements
     *
     * @var array
     * @access private
     */
    private $elements = array();

    /**
     *
     * Add new menu element
     *
     * @param \SilverWp\ShortCode\Vp\Menu\Element\ElementInterface $element
     *
     * @return $this
     * @access public
     */
    public function addElement( ElementInterface $element ) {
        if ( \in_array( $element, $this->elements ) ) {
            return $this;
        }
        $this->elements[ ] = $element;

        return $this;
    }

    /**
     *
     * Get array with full list of registered menu elements
     *
     * @return array array with list off all elements and fields
     * @access public
     */
    public function getElements() {
        $elements = array();
        foreach ( $this->elements as $element_class ) {
            $elements[ 'elements' ][ $element_class->getName() ] = $element_class->getSettings();
        }

        return $elements;
    }

    /**
     * Set menu title
     *
     * @param string $title
     *
     * @return $this
     * @access public
     */
    public function setTitle( $title ) {
        $this->title = $title;

        return $this;
    }

    /**
     *
     * Get title
     *
     * @return string
     * @access public
     */
    public function getTitle() {
        return $this->title;
    }
}
