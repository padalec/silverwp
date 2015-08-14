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
namespace SilverWp\Taxonomy;

use SilverWp\Debug;
use SilverWp\Helper\Filter;
use SilverWp\Helper\UtlArray;
use SilverWp\PostInterface;
use SilverWp\PostType\PostTypeInterface;
use SilverWp\SingletonAbstract;
use SilverWp\Translate;

if ( ! class_exists( '\SilverWp\Taxonomy\TaxonomyAbstract' ) ) {
	/**
	 *
	 * Register new taxonomy to Post Type
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       0.2
	 * @category      WordPress
	 * @package       SilverWp
	 * @subpackage    Taxonomy
	 * @abstract
	 * @copyright (c) 2009 - 2015, SilverSite.pl
	 */
	abstract class TaxonomyAbstract extends SingletonAbstract
		implements TaxonomyInterface, PostInterface {

		/**
		 * Handler for post type class
		 *
		 * @var array
		 * @access private
		 */
		private $posts_types_handler = array();

		/**
		 * Taxonomies to register
		 *
		 * @var array sample:
		 * array(
		 *  'name'   =>  array(
		 *          'public'            => true,
		 *          'show_in_nav_menus' => true,
		 *          'show_ui'           => true,
		 *          'show_tagcloud'     => true,
		 *          'hierarchical'      => true,
		 *          'query_var'         => true
		 *      ),
		 * )
		 */
		protected $taxonomies = array();

		/**
		 * Post id
		 *
		 * @var integer
		 * @access private
		 */
		private $post_id = null;

		/**
		 *
		 * Class constructor
		 *
		 * @access protected
		 */
		protected function __construct() {
			// Adds taxonomies
			add_action( 'init', array( $this, 'init' ) );
			// Allows filtering of posts by taxonomy in the admin view
			add_action( 'restrict_manage_posts', array( $this, 'filterAdminPostsTypeList' ) );
			add_filter( 'parse_query', array( $this, 'addFilter2QueryList' ), 10, 1 );
		}

		/**
		 * Add new taxonomy
		 *
		 * @param string $taxonomy_name - unique taxonomy name
		 * @param array  $args          - all taxonomy params @see https://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
		 *
		 * @return $this
		 * @access public
		 */
		public function add( $taxonomy_name, array $args ) {
			$this->taxonomies[ $taxonomy_name ] = $args;

			return $this;
		}

		/**
		 * Change default labels for taxonomy
		 *
		 * @param string $taxonomy_name
		 * @param array  $labels (@see labels: https://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments)
		 *
		 * @return $this
		 * @access public
		 */
		public function setLabels( $taxonomy_name, array $labels ) {
			$this->taxonomies[ $taxonomy_name ]['labels'] = $labels;

			return $this;
		}

		/**
		 * Set PostType class
		 *
		 * @param PostTypeInterface $post_type_class
		 *
		 * @return $this
		 * @access public
		 */
		public function setPostTypeHandler( PostTypeInterface $post_type_class ) {
			$this->posts_types_handler = array();
			$this->posts_types_handler[] = $post_type_class;

			return $this;
		}

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
		public function addPostTypeHandler( PostTypeInterface $post_type_class ) {
			$this->posts_types_handler[] = $post_type_class;

			return $this;
		}

		/**
		 * Set post id
		 *
		 * @param integer $post_id
		 *
		 * @return $this
		 * @access public
		 */
		public function setPostId( $post_id ) {
			$this->post_id = (int) $post_id;

			return $this;
		}

		/**
		 * Get post id
		 *
		 * @return integer
		 * @access public
		 */
		public function getPostId() {
			return $this->post_id;
		}

		/**
		 *
		 * Get post type class handler
		 *
		 * @return string
		 * @access public
		 */
		public function getPostsTypesHandler() {
			return $this->posts_types_handler;
		}

		/**
		 * Set up taxonomy class labels etc.
		 *
		 * @since  0.2
		 * @abstract
		 * @access protected
		 */
		abstract protected function setUp();

		/**
		 *
		 * Register taxonomy
		 *
		 * @access public
		 * @throws Exception
		 */
		public function init() {
			$this->setUp();

			if ( \is_null( $this->posts_types_handler ) ) {
				throw new Exception(
					Translate::translate(
						__CLASS__ . '::posts_types_handler is required and can\'t be empty.'
					)
				);
			}

			$post_type_objects = $this->getPostsTypesNames();

			foreach ( $this->taxonomies as $taxonomy_name => $args ) {
				//register taxonomy
				register_taxonomy( $taxonomy_name, $post_type_objects, $args );

				foreach ( $post_type_objects as $post_type_object ) {
					//add taxonomy to Post Type
					register_taxonomy_for_object_type( $taxonomy_name, $post_type_object );
				}

				if ( isset( $args['custom_meta_box'] ) && ! empty( $args[ 'custom_meta_box' ] ) ) {
					$this->changeDefaultMetaBox($taxonomy_name, $args[ 'custom_meta_box' ]);
				}
			}
		}

		/**
		 * Return all post types names registered with taxonomy
		 *
		 * @return array
		 * @access private
		 */
		private function getPostsTypesNames() {
			$post_type_name = array();
			foreach ( $this->getPostsTypesHandler() as $post_type ) {
				$post_type_name[] = $post_type->getName();
			}

			return $post_type_name;
		}

		/**
		 * This function return all taxonomies with his
		 * arguments or all argument of $taxonomy_name
		 *
		 * @param null $taxonomy_name
		 *
		 * @return array
		 * @access public
		 */
		public function get( $taxonomy_name = null ) {
			if ( ! is_null( $taxonomy_name )
			     && isset( $this->taxonomies[ $taxonomy_name ] )
			) {
				return $this->taxonomies[ $taxonomy_name ];
			}

			return $this->taxonomies;
		}

		/**
		 * Check if taxonomy $taxonomy_name is registered
		 *
		 * @param string $taxonomy_name taxonomy name
		 *
		 * @return boolean
		 * @access public
		 */
		public function isRegistered( $taxonomy_name ) {
			if ( isset( $this->taxonomies[ $taxonomy_name ] ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Add taxonomy filter to the admin page in post type lists
		 *
		 * @link   https://pippinsplugins.com/post-list-filters-for-custom-taxonomies-in-manage-posts/
		 * @access public
		 * @return void
		 */
		public function filterAdminPostsTypeList() {
			global $typenow;
			// An array of all the taxonomyies you want to display. Use the taxonomy name or slug
			// must set this to the post type you want the filter(s) displayed on
			foreach ($this->getPostsTypesNames() as $post_type_name) {
				if ( $typenow == $post_type_name ) {
					$taxonomies = \get_object_taxonomies( $typenow );
					foreach ( $taxonomies as $tax_slug ) {

						$tax_obj = \get_taxonomy( $tax_slug );

						if ( \wp_count_terms( $tax_slug ) ) {
							\wp_dropdown_categories(
								array(
									'show_option_all' => Translate::translate( 'Show All ' . $tax_obj->label ),
									'taxonomy'        => $tax_slug,
									'name'            => $tax_obj->name,
									'orderby'         => 'name',
									'selected'        => Filter::get_var( $tax_slug ),
									'hierarchical'    => $tax_obj->hierarchical,
									'show_count'      => false,
									'hide_empty'      => true
								)
							);
						}
					}
				}
			}
		}
		/**
		 * add teaxonomy slug to query for filter by taxonomy
		 *
		 * @global string $pagenow current page
		 *
		 * @param object  $query   Wp_query instance
		 *
		 * @access public
		 * @return void
		 * @todo   wtf is this?
		 */
		public function addFilter2QueryList( $query ) {
			return ;
			global $pagenow;
			$post_type  = $this->getPostType();
			$taxonomy   = $this->getName( 'category' );
			$query_vars = &$query->query_vars;
			if ( $pagenow == 'edit.php' && isset( $query_vars['post_type'] )
			     && $query_vars['post_type'] == $post_type
			     && isset( $query_vars[ $taxonomy ] )
			     && is_numeric( $query_vars[ $taxonomy ] )
			     && $query_vars[ $taxonomy ] != 0
			) {
				$term                    = get_term_by( 'id',
					$query_vars[ $taxonomy ], $taxonomy );
				$query_vars[ $taxonomy ] = $term->slug;
			}
		}

		/**
		 * @param $taxonomy_name
		 * @param $args
		 *
		 * @access
		 */
		protected function changeDefaultMetaBox( $taxonomy_name, $args ) {
			$custom_tax_mb = new \Taxonomy_Single_Term( $taxonomy_name, $this->getPostsTypesNames(), $args[ 'control_type' ] );
			foreach ( $args as $name => $value ) {
				$custom_tax_mb->set( $name, $value );
			}
		}
	}
}
