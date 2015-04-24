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

if ( ! class_exists( 'SilverWp\Helper\Control\Date' ) ) {

    /**
     *
     * Control date
     * Date is used to pick a date with ability to restrict its format.
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Helper\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Date extends ControlAbstract {
        protected $type = 'date';

        /**
         *
         * Restrict the selectable dates only after the specified min_date.
         * You can use jQuery datepicker value format, like 'today', '+1D'.
         * Or use an exact value (obeying the format you specified).
         *
         * @param string $min_date
         *
         * @return $this
         * @access public
         */
        public function setMin( $min_date ) {
            $this->setting[ 'min_date' ] = $min_date;
            return $this;
        }

        /**
         *
         * Restrict the selectable dates only before the specified max_date.
         * You can use jQuery datepicker value format, like 'yesterday', '-1D +1W'.
         * Or use an exact value (obeying the format you specified).
         *
         * @param string $max_date
         *
         * @return $this
         * @access public
         */
        public function setMax( $max_date ) {
            $this->setting[ 'max_date' ] = $max_date;
            return $this;
        }

        /**
         * Set the format of the date, e.g. 'yy-mm-dd', 'dd-mm-yy'.
         *
         * @param string $format
         *
         * @return $this
         * @access public
         */
        public function setFormat( $format ) {
            $this->setting[ 'format' ] = $format;
            return $this;
        }
    }
}