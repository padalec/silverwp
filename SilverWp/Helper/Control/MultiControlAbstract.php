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
namespace SilverWp\Helper\Control;

if ( ! class_exists( 'SilverWp\Helper\Control\MultiControlAbstract' ) ) {

    /**
     *
     * Abstract base class for controls with multi elements
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Helper\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     * @abstract
     */
    abstract class MultiControlAbstract extends ControlAbstract implements MultiControlInterface {

        /**
         * Set multi options
         *
         * @param array $options
         *
         * @return $this
         * @access public
         */
        public function setOptions( array $options ) {
            $this->setting[ 'items' ] = $options;

            return $this;
        }

        /**
         *
         * Add mew option
         *
         * @param string $value
         * @param string $label
         *
         * @return $this
         * @access public
         */
        public function addOption( $value, $label ) {
            $this->setting[ 'items' ][] = array(
                'value' => $value,
                'label' => $label,
            );

            return $this;
        }

        /**
         *
         * Default value of multifileds, refers to an item choice value or smart tags: {{first}} / {{last}}.
         *
         * @param string $default
         *
         * @return $this
         * @access public
         */
        public function setDefault( $default ) {
            parent::setDefault( array( $default ) );
            return $this;
        }
    }
}