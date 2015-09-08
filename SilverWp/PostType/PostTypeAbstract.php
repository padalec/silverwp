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

use SilverWp\Ajax\PostLike;
use SilverWp\Debug;
use SilverWp\Exception;
use SilverWp\Interfaces\Core;
use SilverWp\MetaBox\Exception as MetaBoxException;
use SilverWp\MetaBox\MetaBoxAbstract;
use SilverWp\MetaBox\MetaBoxInterface;
use SilverWp\PostRelationship\Relationship;
use SilverWp\SingletonAbstract;
use SilverWp\Taxonomy\Exception as TaxonomyException;
use SilverWp\Taxonomy\TaxonomyInterface;
use SilverWp\Translate;

if ( ! class_exists( '\SilverWp\PostType\PostTypeAbstract' ) ) {
	/**
	 * Abstract Post Type
	 *
	 * @property bool  exclude_from_search
	 * @property bool  query_var
	 * @property bool  show_ui
	 * @property array rewrite
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       0.3
	 * @category      WordPress
	 * @package       SilverWp
	 * @subpackage    PostType
	 * @copyright     SilverSite.pl (c) 2015
	 * @tutorial http://blog.teamtreehouse.com/create-your-first-wordpress-custom-post-type
	 */
	abstract class PostTypeAbstract extends SingletonAbstract implements PostTypeInterface, Core {

		/**
		 *
		 * Custom Post Type name (required)
		 *
		 * @var string
		 * @access protected
		 */
		protected $name;

		/**
		 *
		 * An alias for calling add_post_type_support() directly. As of 3.5,
		 * boolean false can be passed as value instead of an array to prevent
		 * default (title and editor) behavior.
		 * Default: title and editor
		 * 'title'
		 * 'editor' (content)
		 * 'author'
		 * 'thumbnail' (featured image, current theme must also support post-thumbnails)
		 * 'excerpt'
		 * 'trackbacks'
		 * 'custom-fields'
		 * 'comments' (also will see comment count balloon on edit screen)
		 * 'revisions' (will store revisions)
		 * 'page-attributes' (menu order, hierarchical must be true to show Parent option)
		 * 'post-formats' add post formats, see Post Formats
		 *
		 * Note: When you use custom post type that use thumbnails remember to check that the
		 * theme also supports thumbnails or use add_theme_support function.
		 *
		 * @var array
		 * @access protected
		 */
		protected $supports = array( 'title', 'editor' );

		/**
		 *
		 *(optional) Whether a post type is intended to be used publicly either
		 * via the admin interface or by front-end users.
		 * Default: false
		 * 'false' - Post type is not intended to be used publicly and should
		 *           generally be unavailable in wp-admin and on the front end
		 *           unless explicitly planned for elsewhere.
		 * 'true' - Post type is intended for public use. This includes on the
		 *          front end and in wp-admin.
		 *
		 * Note: While the default settings of exclude_from_search,
		 *       publicly_queryable, show_ui, and show_in_nav_menus are inherited
		 *       from public, each does not rely on this relationship and controls
		 *       a very specific intention.
		 *
		 * @var boolean
		 * @access protected
		 */
		protected $public = true;

		/**
		 *
		 * Taxonomy object handle
		 *
		 * @var object
		 * @access protected
		 */
		protected $taxonomy_handler = null;

		/**
		 *
		 * meta box object handler
		 *
		 * @var object
		 * @access protected
		 */
		protected $meta_box_handler = null;

		/**
		 * list of post type templates
		 *
		 * @var array
		 * @access protected
		 */
		protected static $page_templates = array();

		/**
		 * image thumbnail size
		 *
		 * @var mixed string or array
		 * @access protected
		 */
		protected $thumbnail_size = 'thumbnail';

		/**
		 * Post type settings handler
		 *
		 * @var array
		 * @access protected
		 */
		protected $args = array();

		/**
		 *
		 * @var array
		 * @access protected
		 */
		protected $labels = array();

		/**
		 *
		 * @var bool
		 * @access protected
		 */
		protected $debug = false;

		/**
		 * Relationship class handler
		 *
		 * @var null|Relationship
		 * @access private
		 */
		private $relationship = null;

		/**
		 *
		 * Class constructor
		 *
		 * @access protected
		 */
		protected function __construct() {
			if ( in_array( 'thumbnail', $this->supports ) ) {
				// Thumbnail support for portfolio posts
				add_theme_support( 'post-thumbnails', array( $this->name ) );
			}
			// Adds new post type
			add_action( 'init', array( $this, 'init' ) );
		}

		/**
		 *
		 * Set up Custom Post Type. In this method will be set up labels and all
		 * register_post_type function arguments
		 *
		 * @abstract
		 * @access protected
		 * @see https://codex.wordpress.org/Function_Reference/register_post_type#Parameters
		 */
		abstract protected function setUp();

		/**
		 * Initialize class and register Custom Post Type
		 *
		 * @access public
		 * @throws \SilverWp\PostType\Exception
		 */
		public function init() {
			$parent_class = get_called_class();
			if ( is_null( $this->name ) ) {
				throw new Exception(
					Translate::translate( 'Property %s is required and can\'t be empty.', $parent_class .'::name' )
				);
			}
			//setup default args
			//display in admin menu
			$this->show_ui             = true;
			$this->query_var           = true;
			$this->exclude_from_search = false;

			// Permalinks format
			$this->rewrite = array(
				'slug'       => $this->name,
				'with_front' => false
			);
			//set up post type
			$this->setUp();

			$default_args = array(
				'labels'   => wp_parse_args( $this->labels, $this->getDefaultLabels() ),
				'public'   => $this->public,
				'supports' => $this->supports,
			);
			$this->args = wp_parse_args( $this->args, $default_args );
			$post_type = register_post_type( $this->name, $this->args );
			flush_rewrite_rules();
			//debug
			if ( $this->debug ) {
				Debug::dumpPrint( $this->args, $this->name );
				Debug::dumpPrint( $post_type );
			}
		}

		/**
		 * Set arguments to register_post_type function
		 * @see https://codex.wordpress.org/Function_Reference/register_post_type#Parameters
		 *
		 * @param string $name param name
		 * @param mixed $value param value
		 *
		 * @access public
		 */
		public function __set( $name, $value ) {
			$this->args[ $name ] = $value;
		}

		/**
		 *
		 * Add post type template
		 *
		 * @param mixed $template_name array or string
		 *
		 * @access public
		 */
		public function addTemplates( $template_name ) {
			self::$page_templates[ $this->name ][] = $template_name;
		}

		/**
		 * Add new support too post type
		 *
		 * @param string $name support name
		 *
		 * @return $this
		 * @access public
		 */
		public function addSupport( $name ) {
			if ( \in_array( $name, $this->supports ) ) {
				return $this;
			}
			$this->supports[] = $name;

			return $this;
		}


		/**
		 * Add relationship between to post type
		 *
		 * @param string                $name unique relationship name
		 * @param null|PostTypeAbstract $to   post type class
		 *
		 * @return Relationship
		 * @access public
		 */
		public function addRelationship( $name, PostTypeAbstract $to = null ) {
			try {
				$this->relationship = new Relationship( $name );
				$this->relationship->setFrom( $this->getName() );
				if ( ! is_null( $to ) ) {
					$this->relationship->setTo( $to );
				}

				return $this->relationship;
			} catch ( Exception $ex ) {
				echo $ex->displayAdminNotice();
			}
		}

		public function getRelationship() {
			return $this->relationship;
		}
		/**
		 * Get meta box object handle
		 *
		 * @return MetaBoxAbstract
		 * @access public
		 */
		public function getMetaBox() {
			return $this->meta_box_handler;
		}

		/**
		 * Get all added templates if post_type isn't null
		 * return all templates directed for post type
		 *
		 * @param null $post_type
		 *
		 * @return array
		 * @static
		 * @access public
		 */
		public static function getTemplates( $post_type = null ) {
			if ( is_null( $post_type ) ) {
				return self::$page_templates;
			} elseif ( isset( self::$page_templates[ $post_type ] ) ) {
				return self::$page_templates[ $post_type ];
			}
		}

		/**
		 *
		 * Get Post Type name
		 *
		 * @return string
		 * @access public
		 */
		public function getName() {
			return $this->name;
		}

		/**
		 *
		 * Get taxonomy class handler
		 *
		 * @return object
		 * @access public
		 */
		public function getTaxonomy() {
			return $this->taxonomy_handler;
		}

		/**
		 * Get Post Type supports
		 *
		 * @return array
		 * @access public
		 */
		public function getSupports() {
			return $this->supports;
		}

		/**
		 * Register meta box too Post Type
		 *
		 * @param MetaBoxInterface $meta_box
		 *
		 * @access public
		 * @return void
		 */
		public function registerMetaBox( MetaBoxInterface $meta_box ) {
			try {

				$this->meta_box_handler = $meta_box;
				$meta_box->setId( $this->name );
				$meta_box->addPostType( $this->name );

			} catch ( MetaBoxException $ex ) {
				echo $ex->displayAdminNotice();
			}
		}

		/**
		 *
		 * Set a taxonomies to Post Type
		 *
		 * @param TaxonomyInterface $taxonomy
		 *
		 * @access public
		 */
		public function registerTaxonomy( TaxonomyInterface $taxonomy ) {
			try {

				$this->taxonomy_handler = $taxonomy;
				$taxonomy->setPostTypeHandler( $this );

			} catch ( TaxonomyException $ex ) {
				echo $ex->displayAdminNotice();
			}
		}

		/**
		 *
		 * Set thumbnail size returned in getQueryMethod
		 *
		 * @param mixed $thumbnail_size string or array
		 *
		 * @return $this
		 * @access public
		 */
		public function setThumbnailSize( $thumbnail_size ) {
			$this->thumbnail_size = $thumbnail_size;

			return $this;
		}

		/**
		 * Check if the taxonomy was registered
		 *
		 * @return boolean
		 * @access public
		 */
		public function isTaxonomyRegistered() {
			if ( \is_null( $this->taxonomy_handler ) ) {
				return false;
			}

			return true;
		}

		/**
		 * check if the meta box class was registered
		 *
		 * @return boolean
		 * @access public
		 */
		public function isMetaBoxRegistered() {
			if ( \is_null( $this->meta_box_handler ) ) {
				return false;
			}

			return true;
		}


		/**
		 *
		 * Default labels
		 *
		 * @return array
		 * @access private
		 */
		private function getDefaultLabels() {

			$labels = array(
				'singular_name'      => Translate::translate( 'Item' ),
				'add_new'            => Translate::translate( 'Add New Item' ),
				'add_new_item'       => Translate::translate( 'Add New Item' ),
				'edit_item'          => Translate::translate( 'Edit Item' ),
				'all_items'          => Translate::translate( 'All Items' ),
				'new_item'           => Translate::translate( 'Add New Item' ),
				'view_item'          => Translate::translate( 'View Item' ),
				'search_items'       => Translate::translate( 'Search' ),
				'not_found'          => Translate::translate( 'No items found' ),
				'not_found_in_trash' => Translate::translate( 'No items found in trash' )
			);

			return $labels;
		}

	}
}
