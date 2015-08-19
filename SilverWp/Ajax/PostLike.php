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

namespace SilverWpAddons\Ajax;

use SilverWp\Ajax\AjaxAbstract;
use SilverWp\Helper\Filter;
use SilverWp\Helper\UtlArray;
use SilverWp\Translate;

if ( ! class_exists( '\SilverWp\PostLike' ) ) {
	/**
	 * Post Like rating system
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       $Id: PostLike.php 2184 2015-01-21 12:20:08Z padalec $
	 * @category      WordPress
	 * @package       SilverWpAddons
	 * @subpackage    Ajax
	 * @link          https://github.com/JonMasterson/WordPress-Post-Like-System based on JonMasterson script
	 * @copyright (c) SilverSite.pl 2015
	 * @TODO          refactor!!
	 */
	class PostLike extends AjaxAbstract {
		/**
		 *
		 * post id
		 *
		 * @var int
		 */
		private $post_id;
		/**
		 *
		 * user id
		 *
		 * @var int
		 */
		private $user_id;

		protected $name = 'post_like';

		/**
		 *
		 * Add Fontawesome Icons
		 *
		 * @access public
		 * @return void
		 */
		public function enqueueIcons() {
			wp_register_style( 'icon-style',
				'http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css' );
			wp_enqueue_style( 'icon-style' );
			wp_register_style( $this->name, ASSETS_URI . 'css/like-styles.css' );
			wp_enqueue_style( $this->name );
		}

		/**
		 *
		 * Save like data
		 *
		 * @global type $current_user
		 */
		public function postLike() {
			$this->checkAjaxReferer();
			$post_like     = (int) $this->getRequestData( 'post_like',
				FILTER_SANITIZE_NUMBER_INT );
			$this->post_id = (int) $this->getRequestData( 'post_id',
				FILTER_SANITIZE_NUMBER_INT ); // post id

			if ( $post_like ) {

				$post_like_count = $this->getPostMeta( '_post_like_count',
					true ); // post like count
				if ( \is_user_logged_in() ) { // user is logged in
					$this->userLoggedLike( $post_like_count );
				} else { // user is not logged in (anonymous)
					// user IP address
					if ( ( $ip = $this->getUserIp() ) !== false ) {
						$liked_ips
							= $this->getPostMeta( '_user_IP' ); // stored IP addresses
						if ( \array_search( $ip, $liked_ips ) === false
						     || \array_search( $ip, $liked_ips ) === null
						) {
							// if IP not in array
							$liked_ips[] = $ip;// add IP to array
						}
						if ( $this->alreadyLiked() ) {// unlike the post
							$ip_key = \array_search( $ip,
								$liked_ips ); // find the key
							unset( $liked_ips[ $ip_key ] ); // remove from array
							// Remove user IP from post meta
							// -1 count post meta
							$post_like_count
								= $this->subtractionLikeCount( $post_like_count );
							$this->updatePostLikeMeta( $liked_ips,
								$post_like_count );
							// generate response
							$this->response( 0, $post_like_count );
						} else {//like the post
							// Add user IP to post meta
							// +1 count post meta
							$this->updatePostLikeMeta( $liked_ips,
								++ $post_like_count );
							// generate response
							// update count on frontend
							$this->response( 1, $post_like_count );
						}
					} else {
						$this->response( 'ip', 'error' );
					}
				}
			} else {
				$this->response( 'param post_like', 'error' );
			}
		}

		/**
		 *
		 * if user logged in change user meta data
		 *
		 * @access protected
		 *
		 * @param int                     $post_like_count post like count
		 *
		 * @global \SilverWpAddons\object $current_user
		 */
		protected function userLoggedLike( $post_like_count ) {
			global $current_user;
			$this->user_id = $current_user->ID; // current user

			$liked_posts = $this->getUserLikeMeta(); // post ids from user meta

			$liked_users
				           = $this->getPostMeta( '_user_liked' ); // user ids from post meta
			$liked_posts[] = $this->post_id; // Add post id to user meta array
			$liked_users[] = $this->user_id; // add user id to post meta array

			$liked_posts
				= UtlArray::array_remove_empty( \array_unique( $liked_posts ) );
			//$liked_users = \array_unique($liked_users);

			$user_likes = \count( $liked_posts ); // count user likes

			if ( $this->alreadyLiked() ) {//unlike the post
				$liked_posts = \array_diff( array( $this->_post_id ),
					$liked_posts ); // find the key
				$liked_users = \array_diff( array( $this->_user_id ),
					$liked_users ); // find the key
				//unset($liked_posts[ $pid_key ]); // remove from array
				//unset($liked_users[ $uid_key ]); // remove from array
				$user_likes = \count( $liked_posts ); // recount user likes

				// Add user ID to post meta
				// +1 count post meta
				$post_like_count
					= $this->subtractionLikeCount( $post_like_count );
				$this->updatePostLikeMeta( $liked_users, $post_like_count );

				if ( \is_multisite() ) { // if multisite support
					// Add post ID to user meta
					// +1 count user meta
					$this->updateUserLikeOption( $liked_posts, $user_likes );

				} else {
					// Add post ID to user meta
					// +1 count user meta
					$this->updateUserLikeMeta( $liked_posts, $user_likes );
					//silverwp_debug_array($liked_users);
					//silverwp_debug_array($liked_posts);
				}
				// update count on front end
				$this->response( 0, $post_like_count );
			} else { // like the post

				// Add user ID to post meta
				// +1 count post meta
				$this->updatePostLikeMeta( $liked_users, ++ $post_like_count );

				if ( \is_multisite() ) { // if multisite support
					// Add post ID to user meta
					// +1 count user meta
					$this->updateUserLikeOption( $liked_posts, $user_likes );

				} else {
					// Add post ID to user meta
					// +1 count user meta
					$this->updateUserLikeMeta( $liked_posts, $user_likes );
				}
				// update count on front end
				$this->response( 1, $post_like_count );
			}
		}

		/**
		 * subtraction like count
		 *
		 * @param integer $post_like_count post like count
		 *
		 * @return integer
		 * @access private
		 */
		private function subtractionLikeCount( $post_like_count ) {
			$like = (int) ( $post_like_count > 0 ? -- $post_like_count : 0 );

			return $like;
		}

		/**
		 *
		 * get user like meta
		 *
		 * @access private
		 * @return array
		 */
		private function getUserLikeMeta() {
			$user_meta = array();
			//check is multisite
			if ( \is_multisite() ) {
				$user_meta = \get_user_option( '_liked_posts', $this->user_id );
			} else {
				$user_meta = \get_user_meta( $this->user_id,
					'_liked_posts' ); // post ids from user meta
			}

			if ( \count( $user_meta ) != 0 ) { // meta exists, set up values
				return $user_meta[0];
			}

			return $user_meta;
		}

		/**
		 * update user option data
		 *
		 * @param array $post_ids
		 * @param type  $user_likes_count
		 */
		private function updateUserLikeOption(
			array $post_ids, $user_likes_count
		) {
			$data = array(
				'_liked_posts'     => $post_ids,
				'_user_like_count' => $user_likes_count,
			);
			foreach ( $data as $key => $value ) {
				if ( $value != '' ) {
					\update_user_option( $this->user_id, $key, $value );
				}
			}
		}

		/**
		 *
		 * update user meta data
		 *
		 * @param array $post_ids        array with post id
		 * @param array $user_like_count array with user like count
		 *
		 * @access private
		 */
		private function updateUserLikeMeta( array $post_ids, $user_like_count
		) {
			$data = array(
				'_liked_posts'     => $post_ids,// Add post ID to user meta
				'_user_like_count' => $user_like_count,// +1 count user meta
			);
			foreach ( $data as $key => $value ) {
				\update_user_meta( $this->user_id, $key, $value );
			}
		}

		/**
		 *
		 * update post like meta box
		 *
		 * @param mixed $user_data       array with user ips or user ids array
		 * @param int   $post_like_count post like count
		 *
		 * @access private
		 */
		private function updatePostLikeMeta( $user_data, $post_like_count ) {
			if ( \is_user_logged_in() ) {
				$data = array(
					'_user_liked'      => $user_data,
					// Remove user ID from post meta
					'_post_like_count' => $post_like_count,
					// -1 count post meta
				);
			} else {
				$data = array(
					'_user_IP'         => $user_data,
					'_post_like_count' => $post_like_count
				);// Remove user IP from post meta
			}
			foreach ( $data as $key => $value ) {
				\update_post_meta( $this->post_id, $key, $value );
			}
		}

		/**
		 *
		 * Generate json response
		 *
		 * @param int $already
		 * @param int $post_like_count
		 *
		 * @return string
		 *
		 * @access protected
		 */
		protected function response( $already, $post_like_count ) {
			$data = array(
				'already' => $already,
				'count'   => $post_like_count,
			);
			parent::responseJson( $data );
		}

		/**
		 *
		 * Test if user already liked post
		 *
		 * @access protected
		 * @return boolean
		 */
		protected function alreadyLiked() {
			if ( \is_user_logged_in() ) { // user is logged in

				$user_id     = \get_current_user_id(); // current user
				$liked_users = $this->getPostMeta( '_user_liked',
					true ); // user ids from post meta
				if ( \is_array( $liked_users )
				     && \array_search( $user_id, $liked_users ) !== false
				) {
					return true;
				}

			} else { // user is anonymous, use IP address for voting

				$liked_ips = $this->getPostMeta( '_user_IP',
					true ); // get previously voted IP address
				if ( ( $ip = $this->getUserIp() )
				     !== false
				) { // Retrieve current user IP
					if ( \is_array( $liked_ips )
					     && \array_search( $ip, $liked_ips ) !== false
					) { // True is IP in array
						return true;
					}
				}
			}

			return false;
		}

		/**
		 *
		 * get current usr ip address
		 *
		 * @return string|boolean if the ip addrss is bad return false else return ip address
		 * @access private
		 */
		private function getUserIp() {
			$ip = Filter::ip();

			return $ip;
		}

		/**
		 *
		 * get meta box data
		 *
		 * @param string  $key    meta box key name
		 * @param boolean $single if true data return only single array else multi array
		 *
		 * @return array
		 */
		private function getPostMeta( $key, $single = false ) {
			$post_meta = \get_post_meta( $this->post_id, $key, $single );

			return $post_meta;
		}

		/**
		 *
		 * Front end button
		 *
		 * @param int $post_id - post id
		 *
		 * @return string html link button
		 */
		public function getPostLikeLink( $post_id ) {
			$this->post_id = $post_id;
			$like_count    = $this->getPostMeta( '_post_like_count',
				true ); // get post likes
			$count         = empty( $like_count ) ? 0 : $like_count;
			if ( $this->alreadyLiked() ) {
				$button = Translate::translate( 'Unlike' );
				$heart  = '<i class="klico-like"></i>'; // full heart
			} else {
				$button = Translate::translate( 'Like' );
				$heart  = '<i class="klico-heart"></i>'; // empty heart
			}
			$output = '<span class="jm-post-like" id="likeit" data-post_id="'
			          . esc_attr( $post_id ) . '">';
			//$output .= '<span class="txt">' . $button . '</span> ' ;
			$output .= '<span class="count">' . $heart . ' ' . $count
			           . '</span></span>';
			$output .= '<span class="loading"></span>';

			return $output;
		}

		/**
		 * Add a shortcode to your posts instead
		 * type [postliker] in your post to output the button
		 *
		 * @todo   move this method to short code class
		 * @access public
		 * @return array
		 */
		public static function getLikeCount( $post_id = null ) {
			if ( \is_null( $post_id ) ) {
				$post_id = \get_the_ID();
			}
			$like_count = \get_post_meta( $post_id, '_post_like_count', true );

			return $like_count;
		}

		/**
		 *
		 * get users post like count
		 *
		 * @param int $post_id post id
		 *
		 * @return int
		 */
		public function getPostLikeCount( $post_id ) {
			$this->post_id = $post_id; // post id
			$like_count    = $this->getPostMeta( '_post_like_count',
				true ); // post like count
			return $like_count;
		}

		public function ajaxResponse() {
			$post_like = $this->postLike();

			return $post_like;
		}
	}
}
