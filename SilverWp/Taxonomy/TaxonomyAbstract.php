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
use SilverWp\Helper\UtlArray;
use SilverWp\Interfaces\Core;
use SilverWp\Interfaces\PostType;
use SilverWp\Helper\Filter;
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
		implements TaxonomyInterface, Core {

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
		 * List of columns that should be
		 * exclude from edit table
		 *
		 * @var array
		 * @access protected
		 * @since 0.5
		 */
		protected $exclude_columns = array();

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
		 * @param string $short_name - unique taxonomy name
		 * @param array  $args       - all taxonomy params @see https://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
		 *
		 * @return $this
		 * @access public
		 */
		public function add( $short_name, array $args ) {
			$post_type_objects = $this->getPostsTypesNames();
			foreach ( $post_type_objects as $post_type_name ) {
				$taxonomy_name = $this->getName( $post_type_name, $short_name );
				$this->taxonomies[ $taxonomy_name ] = $args;
			}

			return $this;
		}

		/**
		 * Change default labels for taxonomy
		 *
		 * @param string $short_name short taxonomy name
		 * @param array  $labels (@see labels: https://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments)
		 *
		 * @return $this
		 * @access public
		 */
		public function setLabels( $short_name, array $labels ) {
			foreach ( $this->getPostsTypesNames() as $post_type ) {
				$taxonomy_name = $this->getName( $post_type, $short_name );
				$this->taxonomies[ $taxonomy_name ][ 'labels' ] = $labels;
			}
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

			if ( ! count( $this->posts_types_handler ) ) {
				throw new Exception(
					Translate::translate(
						__CLASS__ . '::posts_types_handler is required and can\'t be empty.'
					)
				);
			}

			$post_type_objects = $this->getPostsTypesNames();

			foreach ( $this->taxonomies as $name => $args ) {
				//add taxonomy to Post Type
				foreach ( $post_type_objects as $post_type_object ) {
					//register taxonomy
					register_taxonomy( $name, $post_type_object, $args );
					register_taxonomy_for_object_type( $name, $post_type_object );
					//if taxonomy have custom_meta_box args replace default MB for custom
					if ( isset( $args['custom_meta_box'] ) && ! empty( $args[ 'custom_meta_box' ] ) ) {
						$this->changeDefaultMetaBox( $name, $args[ 'custom_meta_box' ] );
					}
				}
			}
			$this->manageColumns();
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
		 * Get all names of registered taxonomies
		 *
		 * @return array
		 * @access public
		 */
		public function getNames() {
			return array_keys( $this->taxonomies );
		}

		/**
		 * Get full taxonomy name combined with post typ name and tax name
		 *
		 * @param string $post_type_name
		 * @param string $short_name
		 *
		 * @return string
		 * @access private
		 */
		private function getName( $post_type_name, $short_name ) {
			$taxonomy_name = strtolower( $post_type_name . '_' . $short_name );

			return $taxonomy_name;
		}
		/**
		 * Check if taxonomy $taxonomy_name is registered
		 *
		 * @param string $short_name taxonomy name
		 *
		 * @return boolean
		 * @access public
		 */
		public function isRegistered( $short_name ) {
			$taxonomy_name = $this->getName( $short_name );
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
		 * Add taxonomy slug to query for filter by taxonomy
		 *
		 * @global string $pagenow current page
		 *
		 * @param object  $query   Wp_query instance
		 *
		 * @access public
		 * @return void
		 * @TODO Fix fatal error getName method
		 */
		public function addFilter2QueryList( $query ) {
			return;
			global $pagenow;
			$post_type  = get_post_type();
			$taxonomy   = $this->get( 'category' );
			$query_vars = &$query->query_vars;
			if ( $pagenow == 'edit.php'
			     && isset( $query_vars['post_type'] )
			     && $query_vars['post_type'] == $post_type
			     && isset( $query_vars[ $taxonomy ] )
			     && is_numeric( $query_vars[ $taxonomy ] )
			     && $query_vars[ $taxonomy ] != 0
			) {
				$term  = get_term_by( 'id', $query_vars[ $taxonomy ], $taxonomy );
				$query_vars[ $taxonomy ] = $term->slug;
			}
		}

		/**
		 * Replace default meta box in edit/add view of Post Types
		 *
		 * @param $taxonomy_name
		 * @param $args
		 *
		 * @access private
		 */
		private function changeDefaultMetaBox( $taxonomy_name, $args ) {
			$custom_tax_mb = new \Taxonomy_Single_Term( $taxonomy_name, $this->getPostsTypesNames(), $args[ 'control_type' ] );
			foreach ( $args as $name => $value ) {
				$custom_tax_mb->set( $name, $value );
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
		 * Manage custom columns in edit screen
		 *
		 * @access private
		 */
		private function manageColumns() {
			if ( is_admin() ) {
				// Adds columns in the admin view for thumbnail and taxonomies
				foreach ( $this->posts_types_handler as $post_type ) {
					$post_type_name = $post_type->getName();
					add_filter( 'manage_' . $post_type_name . '_posts_columns', array( $this, 'setColumnsLabels' ), 10, 1 );
					add_action( 'manage_' . $post_type_name . '_posts_custom_column', array( $this, 'customColumns' ), 10, 2 );
				}
			}
		}

		/**
		 * Add columns labels to edit screen
		 *
		 * @link   http://wptheming.com/2010/07/column-edit-pages/
		 * @access public
		 *
		 * @param array $columns
		 *
		 * @return array
		 */
		public function setColumnsLabels( $columns ) {
			$unique_cols   = array( 'category', 'tag' );
			$columns_list = $this->getEditColumns();
			foreach ( $columns_list as $key => $value ) {
				foreach ( $this->taxonomies as $name => $args ) {
					if ( isset( $args['display_column'] ) && $args['display_column'] ) {
						if ( \in_array( $key, $unique_cols ) ) {
							$key = $name . '_' . $key;
						}

						if ( isset( $value['label'] ) ) {
							$columns[ $key ] = $value['label'];
						} elseif ( isset( $value['html'] ) ) {
							$columns[ $key ] = $value['html'];
						}
					}
				}
			}

			return $columns;
		}

		/**
		 *
		 * Add custom columns in edit screen
		 *
		 * @param string $column column name
		 * @param int    $post_id
		 *
		 * @access public
		 * @since 0.5
		 * @todo move to class
		 */
		public function customColumns( $column, $post_id ) {
			try {
//				todo move to meta box
//              if ( $column == $this->id . '_thumbnail' ) {
//					// Display the featured image in the column view if possible
//					if ( \has_post_thumbnail( $post_id ) ) {
//						\the_post_thumbnail( $this->column_image_size );
//					} else {
//						echo Translate::translate( 'None' );
//					}
//				}
				// Display taxonomies in the column view
				foreach ( $this->taxonomies as $taxonomy_name => $args ) {
					if ( isset( $args['display_column'] ) && $args['display_column'] && $column == $taxonomy_name) {
						if ( has_term( '', $taxonomy_name, $post_id ) ) {
							$terms_list = get_the_term_list( $post_id, $taxonomy_name, '', ', ', '' );

							if ( is_wp_error( $terms_list ) ) {
								throw new Exception(
									$terms_list->get_error_message() . ': ' . $taxonomy_name
								);
							}
							if ( $terms_list ) {
								echo $terms_list;
							} else {
								echo Translate::translate( 'None' );
							}
						}
					}
				}

			} catch ( Exception $ex ) {
				echo $ex->displayAdminNotice();
			}
		}

		/**
		 *
		 * get list of edit columns displayed in lists of Post Type
		 *
		 *
		 * list of columns displayed in dashboard list. Example
		 * array(
		 *       'cb' => array(
		 *           'html' => '<input type="checkbox" />',
		 *       ),
		 *       'title' => array(
		 *           'label' => 'Title',
		 *       ),
		 *       'category' => array(
		 *            'label' => 'Categories',
		 *       ),
		 *       'thumbnail' => array(
		 *           'label' => 'Thumbnail',
		 *       ),
		 *       'tag' => array(
		 *           'label' => 'Tags',
		 *      ),
		 *      'date' => array(
		 *          'label' => 'Date',
		 *      ),
		 *      'author' => array(
		 *          'label' => 'Author',
		 *      ),
		 *  );
		 *
		 * @access protected
		 * @return array
		 */
		protected function getEditColumns() {
			$columns_default = array(
				'cb'                     => array(
					'html' => '<input type="checkbox" />',
				),
				'title'                  => array(
					'label' => Translate::translate( 'Title' ),
				),
				'thumbnail'              => array(
					'label' => Translate::translate( 'Thumbnail' ),
				),
				'author'                 => array(
					'label' => Translate::translate( 'Author' ),
				),
				'date'                   => array(
					'label' => Translate::translate( 'Date' ),
				),
				'category'               => array(
					'label' => Translate::translate( 'Categories' ),
				),
				'tag'                    => array(
					'label' => Translate::translate( 'Tags' ),
				),
			);

			foreach ( $this->taxonomies as $name => $args ) {
				if ( isset( $args[ 'display_column' ] ) && $args[ 'display_column' ] ) {
					$columns_default[ $name ][ 'label' ] = $args[ 'labels' ][ 'name' ];
				}
			}

			$columns = UtlArray::array_remove_part( $columns_default, $this->exclude_columns );

			return $columns;
		}
	}

}
