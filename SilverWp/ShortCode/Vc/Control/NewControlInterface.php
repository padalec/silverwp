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
/*
 Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ShortCode/Form/Control/NewControlInterface.php $
 Last committed: $Revision: 2308 $
 Last changed by: $Author: padalec $
 Last changed date: $Date: 2015-02-02 14:35:21 +0100 (Pn, 02 lut 2015) $
 ID: $Id: NewControlInterface.php 2308 2015-02-02 13:35:21Z padalec $
*/
namespace SilverWp\ShortCode\Vc\Control;


/**
 * If we want to create a new form element this
 * interface is required to implements
 *
 * @category WordPress
 * @package SilverWp
 * @subpackage ShortCode\Form\Control
 * @author Michal Kalkowski <michal at dynamite-studio.pl>
 * @copyright Dynamite-Studio.pl 2014
 * @version $Revision: 2308 $
 */
interface NewControlInterface {

    /**
     * Create new setting form element
     *
     * @param array $settings
     * @param mixed $value
     *
     * @return string
     * @access public
     */
    public function createControl( array $settings, $value );
}