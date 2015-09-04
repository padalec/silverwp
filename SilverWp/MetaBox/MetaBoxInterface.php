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

namespace SilverWp\MetaBox;

if ( ! interface_exists( 'SilverWp\MetaBox\MetaBoxInterface' ) ) {

	/**
	 * Meta box interface
	 *
	 * @author     Michal Kalkowski <michal at silversite.pl>
	 * @version    0.5
	 * @category   WordPress
	 * @package    SilverWp
	 * @subpackage MetaBox
	 */
	interface MetaBoxInterface {

		/**
		 * set meta box unique id
		 *
		 * @param string $id post type name
		 *
		 * @access public
		 */
		public function setId( $id );

		/**
		 *
		 * If need change default label of meta
		 * box enter title hear just put new label to this method
		 *
		 * @param string $title
		 *
		 * @return $this
		 * @access public
		 */
		public function setEnterTitleHearLabel( $title );

		/**
		 *
		 * Set post types
		 *
		 * @param array $post_types
		 *
		 * @return $this
		 * @access public
		 */
		public function setPostTypes( array $post_types );

		/**
		 * Add new post type to post types array
		 *
		 * @param string $post_type
		 *
		 * @return $this
		 * @access public
		 */
		public function addPostType( $post_type );


		public function getAttributes();

		public function getId();

		/**
		 *
		 * Get the registered meta boxes
		 *
		 * @param bool $to_array if true all controls will be
		 *                       flat to ich settings array
		 *
		 * @return array|\SilverWp\Helper\Control\ControlInterface[]
		 * @access public
		 */
		public function getControls( $to_array = false );

		/**
		 * Remove meta boxes from admin dashboard
		 *
		 * @access public
		 */
		public function removeMetaBoxes();

		/**
		 * Change default label in meta box enter title hear
		 *
		 * @param string $title
		 *
		 * @return string
		 * @access public
		 */
		public function changeEnterTitleHearLabel( $title );

	}
}
