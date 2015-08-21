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
namespace SilverWp\PostType;

use SilverWp\MetaBox\MetaBoxInterface;
use SilverWp\Taxonomy\TaxonomyInterface;
if ( ! interface_exists( '\SilverWp\PostType\PostTypeInterface' ) ) {
	/**
	 * Post Type interface
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       $Id: PostTypeInterface.php 2184 2015-01-21 12:20:08Z padalec $
	 * @category      WordPress
	 * @package       SilverWp
	 * @subpackage    PostType
	 * @copyright (c) 2009 - 2014, SilverSite.pl
	 */
	interface PostTypeInterface {
		/**
		 * register meta box too Post Type
		 *
		 * @param \SilverWp\MetaBox\MetaBoxInterface $meta_box
		 *
		 * @access public
		 * @return void
		 */
		public function registerMetaBox( MetaBoxInterface $meta_box );

		/**
		 *
		 * add a taxonomies to Post Type
		 *
		 * @param TaxonomyInterface $taxonomy
		 *
		 * @access public
		 */
		public function registerTaxonomy( TaxonomyInterface $taxonomy );

		/**
		 *
		 * Add post type template
		 *
		 * @param string $template_name array or string
		 *
		 * @access public
		 */
		public function addTemplates( $template_name );

		/**
		 *
		 * Get post type name
		 *
		 * @return string
		 * @access public
		 */
		public function getName();

		/**
		 * Check if the meta box class was registered
		 *
		 * @return boolean
		 * @access public
		 */
		public function isMetaBoxRegistered();

		/**
		 * Check if the taxonomy was registered
		 *
		 * @return boolean
		 * @access public
		 */
		public function isTaxonomyRegistered();

		/**
		 * Check the post type have thumbnail
		 *
		 * @return boolean
		 * @access public
		 */
		public function isThumbnail();

		/**
		 * Check the post type have description
		 *
		 * @return boolean
		 * @access public
		 */
		public function isDescription();

		/**
		 * Check the post type supports title
		 *
		 * @return boolean
		 * @access public
		 */
		public function isTitle();
	}
}
