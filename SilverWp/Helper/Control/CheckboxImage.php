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

if ( ! class_exists( '\SilverWp\Helper\Control\CheckboxImage' ) ) {

    /**
     * CheckBox is equal to HTML's <input type="checkbox" /> tag.
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Helper\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class CheckboxImage extends MultiControlAbstract {

        protected $type = 'checkimage';

        /**
         *
         * Set max image height
         *
         * @param int $max_height
         *
         * @return $this
         * @access public
         */
        public function setImageMaxHeight( $max_height ) {
            $this->setting[ 'item_max_height' ] = $max_height;

            return $this;
        }

        /**
         *
         * Set max image width
         *
         * @param int $max_width
         *
         * @return $this
         * @access public
         */
        public function setImageMaxWidth( $max_width ) {
            $this->setting[ 'item_max_width' ] = $max_width;

            return $this;
        }
    }
}