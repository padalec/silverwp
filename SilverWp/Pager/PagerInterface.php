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

namespace SilverWp\Pager;

if ( ! interface_exists( 'SilverWp\Pager\PagerInterface' ) ) {

	/**
	 * Pager Interface
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       0.2
	 * @category      WordPress
	 * @package       SilverWp
	 * @subpackage    Helper
	 * @copyright (c) 2009 - 2014, SilverSite.pl
	 */
	interface PagerInterface {

		/**
		 * Set total pages
		 *
		 * @param $total_page
		 *
		 * @return $this
		 * @access public
		 */
		public function setTotalPages( $total_page );

		/**
		 * Set current page
		 *
		 * @param int $current_page
		 *
		 * @return $this
		 * @access public
		 */
		public function setCurrentPage( $current_page );

		/**
		 * Get total pager pages
		 *
		 * @return $this
		 * @access public
		 */
		public function getTotalPages();

		/**
		 * Get current pager page
		 *
		 * @return $this
		 * @access public
		 */
		public function getCurrentPage();

		/**
		 * Get pager links
		 *
		 * @return array
		 * @access public
		 */
		public function getLinks();

		/**
		 * Set parameter to paginate_links() function
		 *
		 * @link http://codex.wordpress.org/Function_Reference/paginate_links#Parameters
		 * @param string $name
		 * @param mixed  $value
		 *
		 * @return $this
		 * @access public
		 */
		public function __set( $name, $value );

		/**
		 * Set preview arrow
		 *
		 * @param string $prev_arrow
		 *
		 * @return $this
		 * @access public
		 */
		public function setPrevArrow( $prev_arrow );

		/**
		 * Set next arrow
		 *
		 * @param string $prev_arrow
		 *
		 * @return $this
		 * @access public
		 */
		public function setNextArrow( $prev_arrow );

		/**
		 * Set HTML tag before opening <a href
		 *
		 * @param string $html_tag
		 *
		 * @return $this
		 * @access public
		 */
		public function setTagBeforeHref( $html_tag );

		/**
		 * @param $html_tag
		 *
		 * @return $this
		 * @access public
		 */
		public function setTagAfterHref( $html_tag );

		/**
		 * Set css class to prev href
		 *
		 * @param string $css_class
		 *
		 * @return $this
		 * @access public
		 */
		public function setPrevHrefClass( $css_class );

		/**
		 * Set css class to next href
		 *
		 * @param string $css_class
		 *
		 * @return $this
		 * @access public
		 */
		public function setNextHrefClass( $css_class );

		/**
		 * Set css class to dots span
		 *
		 * @param string $css_class
		 *
		 * @return $this
		 * @access public
		 */
		public function setDotsClass( $css_class );

		/**
		 * Get prev arrow
		 *
		 * @return string
		 * @access public
		 */
		public function getPrevArrow();

		/**
		 * Get next arrow
		 *
		 * @return string
		 * @access public
		 */
		public function getNextArrow();

		/**
		 * Convert object to string (display links)
		 *
		 * @return string
		 * @access public
		 */
		public function __toString();
	}
}