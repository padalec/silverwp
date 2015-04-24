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

use SilverWp\Helper\RecursiveArray;
use SilverWp\Helper\UtlArray;

if ( ! class_exists( 'SilverWp\Helper\Control\Group' ) ) {

    /**
     * WordPress supports unlimited number of metaboxes on it's post edit page,
     * therefore you can define as many metaboxes as you need, so you can use that
     * nature of metaboxes as grouping. But there are times when you need to group your control
     * fields inside a single metabox, to achieve that Vafpress Framework supports
     * control fields grouping inside metabox, which will affects the metabox logically and visually.
     * There are 2 types of metabox group supported in Vafpress Framework, each will be explained below:
     * Fixed group is a type of group in a fixed numbers, so it can't be dynamically replicated.
     * Here is an example to define a fixed group, observe the repeating and length
     * attribute in the array.
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Helper\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Group extends ControlAbstract {

        /**
         * Class constructor
         *
         * @param string $name field name
         *
         * @access public
         */
        public function __construct( $name ) {
            parent::__construct($name);
            $this->setRepeating(false);
        }
        /**
         *
         * Control type
         *
         * @var string
         * @access protected
         */
        protected $type = 'group';

        /**
         *
         * Set Group should by repeating or not
         *
         * @param bool $repeating true/false
         *
         * @return $this
         * @access public
         */
        public function setRepeating( $repeating ) {
            $this->setting[ 'repeating' ] = $repeating;

            return $this;
        }

        /**
         *
         * Set group title
         *
         * @param string $label
         *
         * @return $this
         * @access public
         */
        public function setLabel( $label ) {
            $this->setting[ 'title' ] = $label;

            return $this;
        }

        /**
         *
         * Set group length. This is not documented
         *
         * @param int $length
         *
         * @return $this
         * @access public
         */
        public function setLength( $length ) {
            $this->setting[ 'length' ] = $length;

            return $this;
        }

        /**
         *
         * Add control to group
         *
         * @param \SilverWp\Helper\Control\ControlInterface $control
         *
         * @return $this
         * @access public
         */
        public function addControl( ControlInterface $control ) {
            $this->setting[ 'fields' ][ ] = $control->getSettings();
            return $this;
        }

        /**
         *
         * Get all group controls
         *
         * @return array
         * @access public
         */
        public function getControls() {
            return $this->setting[ 'fields' ];
        }

        /**
         *
         * Set group is sortable
         *
         * @param boolean $sortable
         *
         * @return $this
         * @access public
         */
        public function setSortable( $sortable ) {
            $this->setting[ 'sortable' ] = $sortable;
            return $this;
        }

        /**
         *
         * Remove control from group
         *
         * @param string $name control name
         *
         * @return $this
         * @access public
         */
        public function removeControl( $name ) {
            $key = RecursiveArray::search( $this->setting[ 'fields' ], $name );
            unset( $this->setting[ 'fields' ][ $key ] );
            return $this;
        }
    }
}