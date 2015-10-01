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

namespace SilverWp\Helper;

use SilverWp\Helper\Option;
use SilverWp\Helper\PostType;

/**
 *
 * Function helper for image Thumbnail
 *
 * @author        Michal Kalkowski <michal at silversite.pl>
 * @version       0.4
 * @category      WordPress
 * @package       SilverWp
 * @subpackage    Helper
 * @copyright     2009 - 2015 (c) SilverSite.pl
 */
class Thumbnail {
	/**
	 * class constructor
	 *
	 */
	public function __construct() {
		//\add_action('pre_post_update', array( $this, 'galleryMenuOrderFix' ));
	}

	/**
	 *
	 * Set image sizes
	 *
	 * @param array|string $image_sizes path to file with
	 *                                  image sizes array or
	 *                                  array with sizes
	 *
	 * @access public
	 * @return void
	 */
	public function setImageSize( $image_sizes ) {
		// Thumbnail support for portfolio posts
		if ( \function_exists( 'add_theme_support' ) ) {

			if ( ! is_array( $image_sizes ) && file_exists( $image_sizes ) ) {
				$image_sizes = require $image_sizes;
			}

			\add_theme_support( 'post-thumbnails' );

			foreach ( $image_sizes as $type => $image_size ) {

				if ( 'default' == $type ) {
					$this->defaultImageSize( $image_size );
				}

				if ( 'custom' == $type ) {
					$this->registerCustomImageSize( $image_size );
				}
			}
		}
	}

	/**
	 *
	 * Replace default image size based in settings->media
	 *
	 * @param array $image_sizes array with all image size
	 *
	 * @access private
	 * @return void
	 */
	private function defaultImageSize( array $image_sizes ) {
		foreach ( $image_sizes as $name => $defualt ) {
			Option::update_option( $name . '_size_w', $defualt['width'] );
			Option::update_option( $name . '_size_h', $defualt['height'] );
			$crop = $defualt['crop'] ? 1 : 0;

			if ( 'post-thumbnail' == $name ) {
				//add icon image
				\set_post_thumbnail_size( $defualt['width'], $defualt['height'],
					$crop );
			}
			if ( false === Option::get_option( $name . '_crop' ) ) {
				Option::add_option( $name . '_crop', $crop );
			} else {
				Option::update_option( $name . '_crop', $crop );
			}
		}
	}

	/**
	 *
	 * register image to custom post type
	 *
	 * @param array $image_sizes
	 *
	 * @access private
	 * @return void
	 */
	private function registerCustomImageSize( array $image_sizes ) {
		//get all registered post type
		//$registerd_post_types = PostType::get_all_registered();
		//silverwp_debug_array($image_sizes);
		foreach ( $image_sizes as $key => $images ) {
			\add_image_size( $key, $images['width'], $images['height'],
				$images['crop'] );
			/*if (\is_numeric($post_type)) {*/
			//if new image have to be add for all post type
			//and post_type is index in array loop by every post type
			/*foreach ($registerd_post_types as $name) {

			}*/
			/*
			} else {
				//if only to selected post type
				if (PostType::is_registered($post_type)) {
					foreach ($images as $index => $size) {
						\add_image_size($post_type . '-' . $index, $size['width'], $size['height'], $size['crop']);
					}
				}
			}*/
		}
	}

	/**
	 *
	 * get attachment ID from URL
	 *
	 * @param int $attachment_url - Attachment URL
	 *
	 * @return int
	 * @source http://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
	 * @static
	 */
	public static function getAttachmentIdFromUrl( $attachment_url = '' ) {
		global $wpdb;
		$attachment_id = false;
		// If there is no url, return.
		if ( $attachment_url == '' ) {
			return;
		}
		// Get the upload directory paths
		$upload_dir_paths = wp_upload_dir();
		// Make sure the upload path base directory exists in the attachment URL,
		// to verify that we're working with a media library image
		if ( strpos( $attachment_url, $upload_dir_paths['baseurl'] )
		     !== false
		) {
			// If this is the URL of an auto-generated thumbnail, get the URL of the original image
			//$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
			// Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/',
				'', $attachment_url );
			// Finally, run a custom database query to get the attachment ID from the modified attachment URL
			$sql
				           = "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'";
			$Query         = $wpdb->prepare( $sql, $attachment_url );
			$attachment_id = $wpdb->get_var( $Query );
		}

		return $attachment_id;
	}

	/**
	 *
	 * delete all attachment from directed post
	 *
	 * @param int $post_id current post id
	 *
	 * @return array Array with all deleted attachment
	 * @static
	 */
	public static function deletePostAttachment( $post_id ) {
		$attachment_info = array();
		$args            = array(
			'post_parent' => $post_id
		);
		// Delete's each post.
		$post_attachments = \get_children( $args );
		if ( $post_attachments ) {
			foreach ( $post_attachments as $key => $attachment ) {
				$attachment_info[ $key ]
					= \wp_delete_attachment( $attachment->ID, true );
			}
		}

		return $attachment_info;
	}

	/**
	 *
	 * resize and save image file
	 *
	 * @param string $image_src image file to resize with full path
	 * @param string $save_path path when resized image shouldby saved
	 *
	 * @return array Array with all image information
	 * @static
	 */
	public static function resizeImage( $image_src, $save_path = null ) {
		if ( is_null( $save_path ) ) {
			$wp_upload_dir = \wp_upload_dir();
			$path          = $wp_upload_dir['path'];
		} else {
			$path = $save_path;
		}

		$image_size = get_intermediate_image_sizes();

		foreach ( $image_size as $s ) {
			$width  = \get_option( $s . '_size_w' );
			$height = \get_option( $s . '_size_h' );
			$image
			        = \wp_get_image_editor( $image_src ); // Return an implementation that extends <tt>WP_Image_Editor</tt>
			if ( ! is_wp_error( $image ) ) {
				$image->resize( $width, $height, true );
				$img = $image->save( $path . basename( $image_src ) );
			}
		}

		return $img;
	}

	/**
	 *
	 * add new image to post
	 *
	 * @param int     $post_id     The ID of the post this attachment is for.
	 * @param string  $filename    should be the path to a file in the upload directory.
	 * @param boolean $is_featured if this file should by a featured image set to true default false
	 *
	 * @access public
	 * @static
	 */
	public static function addImage( $post_id, $filename, $is_featured = false
	) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		// Check the type of tile. We'll use this as the 'post_mime_type'.
		$filetype = \wp_check_filetype( basename( $filename ), null );

		// Get the path to the upload directory.
		$wp_upload_dir = \wp_upload_dir();

		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/'
			                    . basename( $filename ),
			'post_mime_type' => $filetype['type'],
			'post_title'     => \preg_replace( '/\.[^.]+$/', '',
				basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Insert the attachment.
		$attach_id = \wp_insert_attachment( $attachment, $filename, $post_id );
		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = \wp_generate_attachment_metadata( $attach_id,
			$filename );
		\wp_update_attachment_metadata( $attach_id, $attach_data );

		if ( $is_featured ) {
			\set_post_thumbnail( $post_id, $attach_id );
		}
	}

	/**
	 * gallery shortcode sort fix
	 * - get the gallery shortcode
	 * - extract the ids
	 * - save the ids as menu_order for the current images.
	 *
	 * @link   http://wordpress.org/support/topic/when-is-menu_order-set-for-attachments#post-3816412
	 *
	 * @param int $id
	 *
	 * @access public
	 */
	public function galleryMenuOrderFix( $id ) {
		//$regex_pattern = get_shortcode_regex();
		$regex_matches = array();
		$content       = Filter::post_var( 'content' );
		preg_match( '/\[gallery[^\]]*\](.*)/', stripslashes( $content ),
			$regex_matches );
		if ( substr( $regex_matches[0], 1, 7 ) == 'gallery' ) {
			$attributes = array();
			preg_match( '/"([^"]+)"/', $regex_matches[0], $attributes );
			$attributes = $attributes[1];
		}

		$ids    = explode( ',', $attributes );
		$images = get_posts(
			array(
				'post_parent'    => $id,
				'numberposts'    => '-1',
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'orderby'        => 'menu_order ID',
				'order'          => 'ASC'
			)
		);

		if ( ! empty( $images ) ) {
			foreach ( $images as $attachment_id => $attachment ) {
				if ( \in_array( $attachment->ID, $ids ) ) {
					$update_post               = array();
					$update_post['ID']         = $attachment->ID;
					$update_post['menu_order'] = \array_search( $attachment->ID,
						$ids );
					\wp_update_post( $update_post );
				}
			}
		}
	}
}
