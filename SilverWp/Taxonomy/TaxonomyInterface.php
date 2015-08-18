<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Taxonomy/TaxonomyInterface.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: TaxonomyInterface.php 2184 2015-01-21 12:20:08Z padalec $
 */

namespace SilverWp\Taxonomy;

use SilverWp\PostType\PostTypeInterface;

if ( ! interface_exists( '\SilverWp\Taxonomy\TaxonomyInterface' ) ) {
	/**
	 * Taxonomy interface
	 *
	 * @author     Michal Kalkowski <michal at silversite.pl>
	 * @version    0.2
	 * @category   WordPress
	 * @package    SilverWp
	 * @subpackage Taxonomy
	 * @copyright  2009 - 2015 (c) SilverSite.pl
	 */
	interface TaxonomyInterface {

		/**
		 * Add new taxonomy
		 *
		 * @param string $taxonomy_name - unique taxonomy name
		 * @param array  $args          - all taxonomy params @see https://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
		 *
		 * @return $this
		 * @access public
		 */
		public function add( $taxonomy_name, array $args );

		/**
		 * Change default labels for taxonomy
		 *
		 * @param string $taxonomy_name
		 * @param array  $labels (@see labels: https://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments)
		 *
		 * @return $this
		 * @access public
		 */
		public function setLabels( $taxonomy_name, array $labels );

		/**
		 * Set PostType class
		 *
		 * @param PostTypeInterface $post_type_class
		 *
		 * @return $this
		 * @access public
		 */
		public function setPostTypeHandler( PostTypeInterface $post_type_class );

		/**
		 * Add new Post Type class
		 * Some of taxonomy can be displayed in different post types
		 * so this method add our taxonomy to Custom Post Type
		 *
		 * @param PostTypeInterface $post_type_class
		 *
		 * @return $this
		 * @access public
		 */
		public function addPostTypeHandler( PostTypeInterface $post_type_class );

		/**
		 * Set post id
		 *
		 * @param integer $post_id
		 *
		 * @return $this
		 * @access public
		 */
		public function setPostId( $post_id );

		/**
		 * Get post id
		 *
		 * @return integer
		 * @access public
		 */
		public function getPostId();

		/**
		 * Check the taxonomy $name is registered
		 *
		 * @param string $name taxonomy name
		 *
		 * @return boolean
		 * @access public
		 */
		public function isRegistered( $name );

		/**
		 * Add taxonomy filters to the admin page in lists
		 *
		 * @see    https://pippinsplugins.com/post-list-filters-for-custom-taxonomies-in-manage-posts/
		 *
		 * @access public
		 * @return void
		 */
		public function filterAdminPostsTypeList();

		/**
		 * This function return all taxonomies with his
		 * arguments or all argument of $taxonomy_name
		 *
		 * @param null $taxonomy_name
		 *
		 * @return array
		 * @access public
		 */
		public function get( $taxonomy_name = null );

		/**
		 * Get all added Posts Types classes instance
		 *
		 * @return array PostTypeInterface[]
		 * @access public
		 */
		public function getPostsTypesHandler();
	}
}