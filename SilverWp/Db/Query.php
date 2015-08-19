<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Db/Query.php $
  Last committed: $Revision: 1572 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2014-10-02 13:22:19 +0200 (Cz, 02 paź 2014) $
  ID: $Id: Query.php 1572 2014-10-02 11:22:19Z padalec $
 */

namespace SilverWp\Db;

use SilverWp\Debug;
use SilverWp\Helper\MetaBox;
use SilverWp\Helper\RecursiveArray;
use SilverWp\PostType\PostTypeAbstract;
use SilverWpAddons\Ajax\PostLike;

if ( ! class_exists( '\SilverWp\Db\Query' ) ) {

	/**
	 * Db query
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       0.1
	 * @category      WordPress
	 * @package       Db
	 * @copyright     2015 (c) SilverSite.pl
	 * @since         0.2
	 */
	class Query extends \WP_Query {

		/**
		 *
		 * @var object|string
		 * @access private
		 */
		private $post_type;

		/**
		 * @var string
		 * @access private
		 */
		private $meta_box_id;

		/**
		 * Class constructor
		 *
		 * @param array|string $query_args
		 * @access public
		 */
		public function __construct( $query_args ) {
			if ( isset( $query_args[ 'post_type' ] ) ) {
				$this->setPostType( $query_args[ 'post_type' ] );
			}
			if ( isset( $query_args[ 'meta_box_id' ] ) ) {
				$this->setPostType( $query_args[ 'meta_box_id' ] );
			}
			parent::__construct( $query_args );
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
			} else {
				$name = $post_type;
			}
			$this->set( 'post_type', $name );
			$this->parse_query_vars();

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
		 * Set meta box id
		 *
		 * @param string $meta_box_id
		 *
		 * @return $this
		 * @access pubic
		 */
		public function setMetaBoxId( $meta_box_id ) {
			$this->meta_box_id = $meta_box_id;

			return $this;
		}

		public function setLimit( $limit ) {
			$this->max_num_pages = (int) $limit;

			return $this;
		}

		public function setOffset( $offset ) {
			$this->set( 'offset', $offset );
			$this->parse_query_vars();
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
		public function setArgs( array $query_args ) {
			foreach ( $query_args as $name => $value ) {
				$this->set( $name, $value );
			}

			return $this;
		}

		public function getLikesCount() {
			$post_like  = PostLike::getInstance();
			$like_count = $post_like->getPostLikeCount( $this->getPostId() );

			return $like_count;
		}

		/**
		 * Get single meta box by name
		 *
		 * @param string $field_name meta box field name
		 *
		 * @param bool   $remove_first remove first element
		 *
		 * @return string|array|boolean
		 * @access public
		 */
		public function getMetaBox( $field_name, $remove_first = true ) {
			$post_id  = $this->getPostId();

			$meta_box = MetaBox::getPostMeta( $this->meta_box_id, $field_name, $post_id, $remove_first );

			if ( is_array( $meta_box ) ) {
				$meta_box = RecursiveArray::removeEmpty( $meta_box );
			}

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
					$return[ 'date' ]    = \get_the_date( '', $post_id );
					$return[ 'weekday' ] = \get_the_date( 'l', $post_id );
					$return[ 'hour' ]    = \get_the_time( '', $post_id );
					break;
				case 'date':
					$return[ 'date' ]    = \get_the_date( '', $post_id );
					$return[ 'weekday' ] = \get_the_date( 'l', $post_id );
					break;
				default:
					$return[ 'date' ]    = \get_the_date( '', $post_id );
					$return[ 'weekday' ] = \get_the_date( 'l', $post_id );
					$return[ 'hour' ]    = \get_the_time( '', $post_id );
					break;
			}

			return $return;
		}

		/**
		 *
		 * Get features list
		 *
		 * @return array
		 * @access public
		 */
		public function getFeatures() {
			$return_array = array();
			$features     = $this->getMetaBox( 'features', true );

			if ( $features ) {
				foreach ( $features['feature'] as $key => $value ) {
					if ( $value['name'] != '' ) {
						$return_array[ $key ] = $value;
					}
				}
			}

			return $return_array;
		}

		/**
		 *
		 * Gallery list
		 *
		 * @param string|array $size thumbnail image size
		 *
		 * @return array
		 */
		public function getGallery( $size = 'thumbnail' ) {
			$images  = array();

			$gallery = $this->getMetaBox( 'gallery_section', false );
			if ( $gallery && count( $gallery ) ) {
				foreach ( $gallery as $key => $gallery_item ) {
					if ( ! is_null( $gallery_item['image'] ) && $gallery_item['image'] != '' ) {

						$gallery_item['attachment_id'] = Thumbnail::getAttachmentIdFromUrl( $gallery_item['image'] );
						$image_html         = \wp_get_attachment_image( $gallery_item['attachment_id'], $size );

						$images[ $key ]  = array(
							'attachment_id' => $gallery_item['attachment_id'],
							'image_url'     => $gallery_item['image'],
							'image_html'    => $image_html,
						);
					}
				}
			}
			return $images;
		}

		/**
		 * Get video data
		 *
		 * @param string $key_name field key name
		 *
		 * @return array
		 */
		public function getMedia( $key_name = 'video' ) {
			$file_data = array();

			$meta_box = $this->getMetaBox( $key_name );

			$video_url = false;
			if ( isset( $meta_box['video_url'] ) && $meta_box['video_url'] ) {
				$video_url = $meta_box['video_url'];
			}

			if ( $video_url ) {
				try {
					$oEmbed = new Oembed( $video_url );

					$file_data['provider_name'] = $oEmbed->provider_name;
					$file_data['file_url']      = $video_url;
					$file_data['thumbnail_url'] = $oEmbed->getThumbnailUrl();

				} catch ( \SilverWp\Exception $ex ) {
					echo Message::alert( $ex->getMessage(), 'alert-danger' );
					if ( WP_DEBUG ) {
						Debug::dumpPrint($ex->getTraceAsString(), 'Stack trace:');
						Debug::dumpPrint($ex->getTrace(), 'Full stack:');
					}
				}
			}

			return $file_data;
		}
	}
}
