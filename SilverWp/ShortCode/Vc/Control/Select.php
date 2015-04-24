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
 Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ShortCode/Form/Element/Select.php $
 Last committed: $Revision: 2184 $
 Last changed by: $Author: padalec $
 Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
 ID: $Id: Select.php 2184 2015-01-21 12:20:08Z padalec $
*/
namespace SilverWp\ShortCode\Vc\Control;

if ( ! class_exists( 'SilverWp\ShortCode\Vc\Control\Select' ) ) {

    /**
     * DropDown input field with set of available options.
     * Array containing the drop down values (either should be a normal array, or an associative array)
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage ShortCode\Vc\Control
     * @author Michal Kalkowski <michal at dynamite-studio.pl>
     * @copyright Dynamite-Studio.pl 2014
     * @version $Id: Select.php 2184 2015-01-21 12:20:08Z padalec $
     */
    class Select extends ControlMultiAbstract {
        protected $type = 'dropdown';

        /**
         * Add param_holder_class to array this is
         * used when we want display color in select
         *
         * @param string $class
         *
         * @return $this
         * @access public
         */
        public function setCssHolderClass( $class ) {
            $this->setting[ 'param_holder_class' ] = $class;

            return $this;
        }
    }
} 