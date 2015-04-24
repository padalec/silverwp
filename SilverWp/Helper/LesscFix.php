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
 Repository path: $HeadURL: $
 Last committed: $Revision: $
 Last changed by: $Author: $
 Last changed date: $Date: $
 ID: $Id: $
*/
namespace SilverWp\Helper;

require_once LIBS_PATH . 'wp-less/vendor/oyejorge/less.php/lessc.inc.php';
if ( class_exists( '\lessc' ) ) {
    /**
     * Fix for less php compiler
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage SilverWp
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     * @link https://github.com/leafo/lessphp/issues/477
     */
    class LesscFix extends \lessc {
        private $inmixin;

        /**
         *
         * @see lessc::compileProp()
         * @param $prop
         * @param $block
         * @param $out
         *
         * @access protected
         */
        protected function compileProp( $prop, $block, $out ) {
            $proceed = true;

            if ( $prop[ 0 ] == 'mixin' ) {
                // We enter a mixin.
                $this->inmixin ++;
            } else if ( $prop[ 0 ] == 'assign' ) {
                list( , $name, $value ) = $prop;

                // This is a @variable assignment.
                if ( $name[ 0 ] == $this->vPrefix ) {

                    // Remove the @ from the variable name.
                    $var = substr( $name, 1 );

                    // This has been defined, we can ignore its definition, except when we are in a mixin
                    // because it has its own scope.
                    if ( isset( $this->registeredVars[ $var ] ) && $this->inmixin == 0 ) {
                        $proceed = false;
                    }
                }
            }

            if ( $proceed ) {
                parent::compileProp( $prop, $block, $out );
            }

            if ( $prop[ 0 ] == 'mixin' ) {
                // We leave a mixin.
                $this->inmixin --;
            }
        }
    }
}