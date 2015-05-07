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
namespace SilverWp\Customizer\Control;

if ( ! class_exists( '\SilverWp\Customizer\Control\Sortable' ) ) {

    /**
     * Customizer sortable control field
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage SilverWp\Customizer\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Sortable extends MultiControlAbstract {
        protected $type = 'sortable';

        public function setDefault( $default ) {
            $this->setting[ 'default' ] = ( is_array( $default ) ? $default : (array) $default );

            return $this;
        }

        public function getValue() {
            // Serialize the defaults array
            $defaults = serialize(
                $this->getDefault()
            );
            // The following will get a serialized array of our options
            $value_serialized = get_theme_mod( $this->getName(), $defaults );
            // Convert the theme mod value to a PHP array
            $value = unserialize( $value_serialized );

            return $value;
        }
    }
}