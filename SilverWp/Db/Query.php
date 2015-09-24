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

namespace SilverWp\Db;

use SilverWp\MetaBox\MetaBoxAbstract;
use SilverWp\MetaBox\MetaBoxInterface;
use SilverWp\PostType\PostTypeAbstract;
use SilverWp\PostType\PostTypeInterface;

if ( ! class_exists( 'SilverWp\Db\Query' ) ) {

	/**
	 * Class extends to WP_Query
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       0.3
	 * @category      WordPress
	 * @package       Db
	 * @copyright     2015 (c) SilverSite.pl
	 * @since         0.2
	 */
	class Query extends \WP_Query {

		/**
		 * Post type class handler or
		 * if string validate post type name. Default: post
		 *
		 * @var PostTypeAbstract|string
		 * @access private
		 */
		private $post_type = 'post';

		/**
		 * @var string|MetaBoxAbstract
		 * @access private
		 */
		private $meta_box = 'post';

		/**
		 * Class constructor
		 *
		 * @param array|string $query_args
		 *
		 * @access public
		 */
		public function __construct( $query_args = null ) {
			if ( isset( $query_args['post_type'] )
			     && $query_args['post_type'] instanceof PostTypeInterface
			) {
				$this->setPostType( $query_args['post_type'] );
				unset( $query_args['post_type'] );
			}
			if ( isset( $query_args['meta_box'] ) ) {
				$this->setMetaBox( $query_args['meta_box'] );
			}
			parent::__construct( $query_args );
		}

		/**
		 * Set current pager page
		 *
		 * @param int $current_page
		 *
		 * @return $this
		 * @access public
		 */
		public function setCurrentPagedPage( $current_page ) {
			$this->is_paged = true;
			$this->set( 'paged', (int) $current_page );

			return $this;
		}

		/**
		 * Add Filter by taxonomy
		 *
		 * @param string     $taxonomy_name
		 * @param string|int $term
		 * @param string     $field
		 *
		 * @return $this
		 * @access public
		 */
		public function addTaxonomyFilter( $taxonomy_name, $term, $field = 'term_id' ) {
			$this->set( 'tax_query',
				array(
					'taxonomy' => $taxonomy_name,
					'field'    => $field,
					'terms'    => $term,
				)
			);

			return $this;
		}

		/**
		 * Set Custom Post Type class handler
		 *
		 * @param string|PostTypeAbstract $post_type
		 *
		 * @return $this
		 * @access public
		 */
		public function setPostType( $post_type ) {
			if ( $post_type instanceof PostTypeAbstract ) {
				$this->post_type = $post_type;
				$name            = $this->post_type->getName();
				if ( $this->post_type->isMetaBoxRegistered() ) {
					$this->setMetaBox( $this->post_type->getMetaBox() );
				}
			} else {
				$name = $post_type;
			}

			$this->set( 'post_type', $name );

			return $this;
		}

		/**
		 * Set meta box id
		 *
		 * @param string|MetaBoxInterface $meta_box
		 *
		 * @return $this
		 * @access pubic
		 */
		public function setMetaBox( $meta_box ) {
			if ( $meta_box instanceof MetaBoxInterface ) {
				$this->meta_box = $meta_box;
				$meta_key = $meta_box->getId();
			}else {
				$meta_key = $meta_box;
			}
			$this->query_vars[ 'meta_key' ] = $meta_key;

			return $this;
		}

		/**
		 * Set post id (this is required in single view)
		 *
		 * @param int $post_id
		 *
		 * @return $this
		 * @access public
		 */
		public function setPostId( $post_id ) {
			$this->set( 'p', $post_id );

			return $this;
		}

		/**
		 * Set query args to WP_Query
		 *
		 * @param array $query_args
		 *
		 * @access public
		 * @return $this
		 */
		public function setQueryArgs( array $query_args ) {
			foreach ( $query_args as $name => $value ) {
				$this->set( $name, $value );
			}
			$this->query( $this->query_vars );

			return $this;
		}

		/**
		 * Set limit post on one page
		 *
		 * @param int $limit
		 *
		 * @return $this
		 * @access public
		 */
		public function setLimit( $limit ) {
			$this->set( 'posts_per_page', (int) $limit );

			return $this;
		}

		/**
		 * Set offset (current page)
		 *
		 * @param int $offset
		 *
		 * @return $this
		 * @access public
		 */
		public function setOffset( $offset ) {
			$this->set( 'offset', $offset );

			return $this;
		}

		/**
		 * Set filter by meta box
		 *
		 * @param string  $key valid meta box key name
		 * @param mixed $value value of meta box key
		 * @param string $compare
		 *
		 * @return $this
		 * @access public
		 */
		public function setMetaBoxFilter( $key, $value, $compare = '==' ) {
			$this->query_vars[ 'meta_query' ][] = array(
				'meta_key'     => $key,
				'meta_value'   => $value,
				'meta_compare' => $compare
			);

			return $this;
		}

		/**
		 * Add filter by meta box
		 *
		 * @param string  $key valid meta box key name
		 * @param mixed $value value of meta box key
		 * @param string $compare
		 *
		 * @return $this
		 * @access public
		 */
		public function addMetaBoxFilter( $key, $value, $compare = '==' ) {
			$this->query_vars[ 'meta_query' ][] = array(
				'meta_key'     => $key,
				'meta_value'   => $value,
				'meta_compare' => $compare
			);

			return $this;
		}

		/**
		 * Get Post Id
		 *
		 * @return int
		 * @access public
		 */
		public function getPostId() {
			return $this->post->ID;
		}

		/**
		 * Get post slug
		 *
		 * @return string
		 * @access public
		 */
		public function getSlug() {
			return $this->post->post_name;
		}

		/**
		 * Get post short description
		 *
		 * @param string $read_more_text
		 *
		 * @return string
		 * @access public
		 */
		public function getShortDescription( $read_more_text = null ) {
			if ( strpos( $this->post->post_content, '<!--more-->' ) !== false ) {
				return get_the_content( $read_more_text );
			} else {
				return get_the_excerpt();
			}
		}

		/**
		 * Get post description
		 *
		 * @return string
		 * @access public
		 */
		public function getDescription() {
			return $this->post->post_content;
		}

		/**
		 * Get post title
		 *
		 * @return string
		 * @access public
		 */
		public function getTitle() {
			return $this->post->post_title;
		}

		/**
		 * Get post type class handler or name
		 *
		 * @return PostTypeAbstract|string
		 * @access public
		 */
		public function getPostType() {
			return $this->post_type;
		}

		/**
		 * Get single meta box by name
		 *
		 * @param string $control_name meta box form control name
		 *
		 * @param bool   $remove_first remove first element
		 *
		 * @return string|array|boolean
		 * @access public
		 */
		public function getMetaBox( $control_name, $remove_first = true ) {
			$post_id = $this->getPostId();
			$meta_box = $this->meta_box->get( $post_id, $control_name, $remove_first );

			return $meta_box;
		}

		/**
		 * Get date by format.
		 *
		 * @param string $date_format Formats: full or date
		 *
		 * @return array
		 * @access public
		 */
		public function getDateByFormat( $date_format ) {
			$post_id = $this->getPostId();
			$return  = array();
			switch ( $date_format ) {
				case 'full':
					$return['date']    = \get_the_date( '', $post_id );
					$return['weekday'] = \get_the_date( 'l', $post_id );
					$return['hour']    = \get_the_time( '', $post_id );
					break;
				case 'date_weekday':
					$return['date']    = \get_the_date( '', $post_id );
					$return['weekday'] = \get_the_date( 'l', $post_id );
					break;
				case 'date':
					$return['date']    = \get_the_date( '', $post_id );
					break;
				case 'date_time':
					$return['date']    = \get_the_date( '', $post_id );
					$return['time']    = \get_the_time( '', $post_id );
					break;
				default:
					$return['date']    = \get_the_date( '', $post_id );
					$return['weekday'] = \get_the_date( 'l', $post_id );
					$return['hour']    = \get_the_time( '', $post_id );
					break;
			}

			return $return;
		}

		/**
		 * Get all post terms
		 *
		 * @param string $taxonomy_name
		 *
		 * @param string $before
		 * @param string $sep
		 * @param string $after
		 *
		 * @return bool|false|string|\WP_Error
		 * @access public
		 * @since  0.4
		 */
		public function getTerms( $taxonomy_name, $before = '', $sep = ', ', $after = '' ) {
			$taxonomy = $this->post_type->getTaxonomy();
			if ( $taxonomy->isRegistered( $taxonomy_name ) ) {
				$tax = $taxonomy->get( $taxonomy_name );
				return get_the_term_list(
					$this->getPostId()
					, $tax['full_name']
					, $before
					, $sep
					, $after
				);
			}

			return false;
		}

		/**
		 * Get current paged page
		 *
		 * @return int
		 * @access public
		 * @since 0.3
		 */
		public function getCurrentPagedPage() {
			$current_page = 1;

			if ( get_query_var( 'paged' ) ) {
				$current_page = get_query_var( 'paged' );
			} else if ( get_query_var( 'page' ) ) {
				$current_page = get_query_var( 'page' );
			}

			return $current_page;
		}

		/**
		 * Get list of related posts
		 *
		 * @return object WP_Query
		 * @access public
		 */
		public function getRelatedPosts() {
			return $this->post_type->getRelationship()->getRelatedPosts();
		}

		/**
		 * Check the post type have thumbnail
		 *
		 * @return boolean
		 * @access public
		 * @since 0.3
		 */
		public function isThumbnail() {
			$post_id = $this->getPostId();
			if ( in_array( 'thumbnail', $this->post_type->getSupport() )
			     && \has_post_thumbnail( $post_id )
			) {
				return true;
			}

			return false;
		}

		/**
		 * Check the post type have description
		 *
		 * @return boolean
		 * @access public
		 * @since 0.3
		 */
		public function isDescription() {
			$editor = \in_array( 'editor', $this->post_type->getSupport() );

			return $editor;
		}

		/**
		 * Check the post type supports title
		 *
		 * @return boolean
		 * @access public
		 * @since 0.3
		 */
		public function isTitle() {
			$is_title = \in_array( 'title', $this->post_type->getSupport() );

			return $is_title;
		}

		/**
		 * Shortcut to MetaBoxAbstract::getThumbnail()
		 *
		 * @param string       $meta_name
		 * @param string|array $size
		 *
		 * @return string
		 * @access public
		 */
		public function getThumbnail( $meta_name, $size ='thumbnail' ) {
			return $this->meta_box
				->getThumbnail( $this->getPostId(), $meta_name, $size );
		}

		/**
		 * Get post sidebar position
		 *
		 * @return string
		 * @access public
		 */
		public function getSidebarPosition() {
			$post_id = $this->getPostId();
			$sidebar = $this->meta_box->getSidebarPosition($post_id);

			return $sidebar;
		}
	}
}