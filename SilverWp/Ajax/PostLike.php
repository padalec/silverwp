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
namespace SilverWp\Ajax;

use SilverWp\Ajax\AjaxAbstract;
use SilverWp\Helper\Filter;
if ( ! class_exists( 'SilverWp\Ajax' ) ) {
	/**
	 * Post Like rating system
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       0.5
	 * @category      WordPress
	 * @package       SilverWp
	 * @subpackage    Ajax
	 * @link          https://github.com/JonMasterson/WordPress-Post-Like-System based on JonMasterson script
	 * @copyright     2009 - 2015 (c) SilverSite.pl
	 */
	class PostLike extends AjaxAbstract {

		/**
		 * In this key we store posts ids liked by user
		 */
		const USER_LIKED_POSTS_KEY = 'liked_posts';

		/**
		 * In this key we store user liked count (this key isn't used yest)
		 */
		const USER_LIKED_POSTS_COUNT_KEY = 'liked_posts_count';

		/**
		 * In this key we stored logged in users liked IDs (post meta)
		 */
		const POST_USERS_LIKED_KEY = '_users_liked_ids';

		/**
		 * This key stored how many post is liked (post meta)
		 */
		const POST_LIKED_COUNT_KEY = '_like_count';

		/**
		 * This key is used to store users liked IPs (post meta)
		 */
		const POST_LIKED_USERS_IP_KEY = '_users_liked_IPs';

		/**
		 *
		 * Voted post Id
		 *
		 * @var int
		 * @access private
		 */
		private $post_id;

		/**
		 *
		 * Logged user ID
		 *
		 * @var int
		 * @access private
		 */
		private $user_id;

		/**
		 * @var string
		 * @see AjaxAbstract::$name
		 */
		protected $name = 'post_like';

		/**
		 * @var string
		 * @see AjaxAbstract::$ajax_js_file
		 */
		protected $ajax_js_file = 'main.js';

		/**
		 * @var string
		 * @see AjaxAbstract::$ajax_handler
		 */
		protected $ajax_handler = 'sage_js';

		/**
		 * Get post like count
		 *
		 * @param int $post_id
		 *
		 * @access public
		 * @return int
		 * @static
		 */
		public static function getLikeCount( $post_id ) {
			$like_count = (int) get_post_meta( $post_id,
				self::POST_LIKED_COUNT_KEY, true );

			return $like_count;
		}

		/**
		 * Ajax response
		 *
		 * @access public
		 */
		public function ajaxResponse() {
			$this->checkAjaxReferer();

			$post_like     = $this->getRequestData( 'post_like',
				FILTER_SANITIZE_NUMBER_INT );
			$this->post_id = $this->getRequestData( 'post_id',
				FILTER_SANITIZE_NUMBER_INT );

			if ( $post_like ) {
				// post like count
				$post_like_count = self::getLikeCount( $this->post_id );
				// user is logged in
				if ( is_user_logged_in() ) {
					$this->userLoggedLike( $post_like_count );
				} else {
					// user is not logged in (anonymous)
					$this->userAnonymousLike( $post_like_count );
				}
			} else {
				$this->response( 'param post_like', 'error' );
			}
		}

		/**
		 *
		 * Test if current user already liked post
		 *
		 * @param int $post_id
		 *
		 * @access public
		 * @return bool
		 * @static
		 */
		public static function isAlreadyLiked( $post_id ) {
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id(); // current user
				// user ids from post meta
				$liked_users = get_post_meta( $post_id,
					self::POST_USERS_LIKED_KEY, true );

				if ( is_array( $liked_users )
				     && array_search( $user_id, $liked_users ) !== false
				) {
					return true;
				}

			} else { // user is anonymous, use IP address for voting
				// get previously voted IP address
				$liked_ips = get_post_meta( $post_id,
					self::POST_LIKED_USERS_IP_KEY, true );
				// Retrieve current user IP
				if ( ( $ip = Filter::ip() ) !== false ) {
					if ( is_array( $liked_ips )
					     && array_search( $ip, $liked_ips ) !== false
					) { // True is IP in array
						return true;
					}
				}
			}

			return false;
		}

		/**
		 *
		 * Generate JSON response
		 *
		 * @param int $already 0/1 user vote or not
		 * @param int $post_like_count
		 *
		 * @return string
		 * @access private
		 */
		private function response( $already, $post_like_count ) {
			$data = array(
				'already' => $already,
				'count'   => $post_like_count,
			);
			parent::responseJson( $data );
		}

		/**
		 * Add like by Anonymous user
		 *
		 * @param int $post_like_count
		 *
		 * @access private
		 */
		private function userAnonymousLike( $post_like_count ) {
			// get user IP address
			if ( ( $ip = Filter::ip() ) !== false ) {
				// stored IP addresses
				$liked_ips = $this->getPostMeta( self::POST_LIKED_USERS_IP_KEY,
					true );
				if ( ! $liked_ips
				     || array_search( $ip, $liked_ips ) === false
				) {
					// if IP not in array
					$liked_ips[] = $ip;// add IP to array
				}
				// unlike the post
				if ( self::isAlreadyLiked( $this->post_id ) ) {
					// find the key
					$ip_key = array_search( $ip, $liked_ips );
					// remove user IP from array
					unset( $liked_ips[ $ip_key ] );
					// Remove user IP from post meta
					// -1 count post meta
					$post_like_count
						= $this->subtractionLikeCount( $post_like_count );
					$this->updatePostLikeMeta( $liked_ips, $post_like_count );
					// generate response
					$this->response( 0, $post_like_count );

				} else {
					//like the post
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

		/**
		 *
		 * If user logged in change user meta data
		 *
		 * @param int $post_like_count post like count
		 *
		 * @access private
		 */
		private function userLoggedLike( $post_like_count ) {
			// current user id
			$this->user_id = get_current_user_id();
			// post ids from user meta
			$liked_posts = $this->getUserLikeMeta();
			// user ids from post meta
			$liked_users = $this->getPostMeta( self::POST_USERS_LIKED_KEY,
				true );
			// Add post id to user meta array
			$liked_posts[] = $this->post_id;
			// add user id to post meta array
			$liked_users[] = $this->user_id;
			// count user likes
			$user_likes = count( $liked_posts );

			//if user like the post and click like - unlike the post
			if ( self::isAlreadyLiked( $this->post_id ) ) {
				// find the key
				$liked_posts = array_diff( $liked_posts,
					array( $this->post_id ) );
				// find the key
				$liked_users = array_diff( $liked_users,
					array( $this->user_id ) );
				$user_likes  = count( $liked_posts ); // recount user likes

				// remove user ID from post
				// -1 count post meta
				$post_like_count
					= $this->subtractionLikeCount( $post_like_count );

				$this->updatePostLikeMeta( $liked_users, $post_like_count );

				// if multi site support
				if ( is_multisite() ) {
					// Add post ID to user meta
					// +1 count user meta
					$this->updateUserLikeOption( $liked_posts, $user_likes );

				} else {
					// Add post ID to user meta
					// +1 count user meta
					$this->updateUserLikeMeta( $liked_posts, $user_likes );
				}
				// update count on front end
				$this->response( 0, $post_like_count );

			} else { // like the post
				// Add user ID to post meta
				// +1 count post meta
				$this->updatePostLikeMeta( $liked_users, ++ $post_like_count );
				// if multi site support
				if ( is_multisite() ) {
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
		 * Subtraction like count
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
		 * Get user like meta
		 *
		 * @access private
		 * @return array
		 */
		private function getUserLikeMeta() {
			$user_id = get_current_user_id();
			//check is multi site
			if ( is_multisite() ) {
				$user_meta = get_user_option( self::USER_LIKED_POSTS_KEY,
					$user_id );
			} else {
				// post ids from user meta
				$user_meta = get_user_meta( $user_id,
					self::USER_LIKED_POSTS_KEY, true );
			}

			return $user_meta;
		}

		/**
		 * Update user option data
		 *
		 * @param array $post_ids
		 * @param int   $user_likes_count
		 */
		private function updateUserLikeOption(
			array $post_ids, $user_likes_count
		) {
			$data = array(
				self::USER_LIKED_POSTS_KEY       => $post_ids,
				self::USER_LIKED_POSTS_COUNT_KEY => $user_likes_count,
			);
			foreach ( $data as $key => $value ) {
				update_user_option( $this->user_id, $key, $value );
			}
		}

		/**
		 *
		 * Update user meta data when I store information about user votes
		 *
		 * @param array $post_ids        array with post id
		 * @param int   $user_like_count user like count
		 *
		 * @access private
		 */
		private function updateUserLikeMeta( array $post_ids, $user_like_count
		) {
			$user_id = get_current_user_id();
			$data    = array(
				// Add post ID to user meta
				self::USER_LIKED_POSTS_KEY       => $post_ids,
				// add hom many user like
				self::USER_LIKED_POSTS_COUNT_KEY => $user_like_count,
			);
			foreach ( $data as $key => $value ) {
				update_user_meta( $user_id, $key, $value );
			}
		}

		/**
		 *
		 * Update post like meta box
		 *
		 * @param array $user_data       array with user ips or user ids array
		 * @param int   $post_like_count post like count
		 *
		 * @access private
		 */
		private function updatePostLikeMeta( array $user_data, $post_like_count
		) {
			if ( is_user_logged_in() ) {
				$data = array(
					//liked users IDs stored in post meta
					self::POST_USERS_LIKED_KEY => $user_data,
					// liked posts count
					self::POST_LIKED_COUNT_KEY => $post_like_count,
				);
			} else {
				$data = array(
					self::POST_LIKED_USERS_IP_KEY => $user_data,
					self::POST_LIKED_COUNT_KEY    => $post_like_count
				);
			}
			foreach ( $data as $key => $value ) {
				$test[] = update_post_meta( $this->post_id, $key, $value );
			}
		}

		/**
		 *
		 * Get meta box data (shortcut to get_post_meta)
		 *
		 * @param string  $key    meta box key name
		 * @param boolean $single if true data return only single array else multi array
		 *
		 * @return array
		 */
		private function getPostMeta( $key, $single = false ) {
			$post_meta = get_post_meta( $this->post_id, $key, $single );

			return $post_meta;
		}
	}
}
