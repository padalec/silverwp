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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/plugins/schedule/vendor/SilverWp/ShortCode/Exception.php $
  Last committed: $Revision: 2624 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-04-24 16:46:53 +0200 (Pt, 24 kwi 2015) $
  ID: $Id: Exception.php 2624 2015-04-24 14:46:53Z padalec $
 */
namespace SilverWp\ShortCode;

if ( ! class_exists( 'SilverWp\ShortCode\Exception' ) ) {
    /**
     * Short code main exception class
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage ShortCode
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision: 2624 $
     */
    class Exception extends \SilverWp\Exception {
    
    }
}