<?php
/*
 * Copyright (C) 2014 Michal Kalkowski <michal at silversite.pl>
 *
 * SilverWp is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * SilverWp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
namespace SilverWp\PostRelationship;

use SilverWp\Debug;
use SilverWp\PostType\PostTypeAbstract;
use SilverWp\SingletonAbstract;
use SilverWp\Exception;
use SilverWp\Translate;

if ( ! class_exists( '\SilverWp\PostRelationship\Relationship' ) ) {

	/**
	 *
	 * Create relationship between two or more post types
	 *
	 * @category   WordPress
	 * @package    SilverWp
	 * @subpackage Schedule
	 * @author     Michal Kalkowski <michal at silversite.pl>
	 * @copyright  Dynamite-Studio.pl & silversite.pl 2015
	 * @version    $Revision:$
	 */
	class Relationship {

		/**
		 *
		 * @var array
		 * @access protected
		 */
		protected $settings = array();

		/**
		 * Class constructor
		 * Create posts relationship
		 *
		 * @access protected
		 *
		 * @param string $name post 2 post relationship unique name
		 */
		public function __construct( $name ) {
			$this->setName( $name );
			add_action( 'p2p_init', array( $this, 'register' ) );
		}

		/**
		 * Set relationship name
		 *
		 * @param string $name unique name
		 *
		 * @return $this
		 * @access public
		 */
		public function setName( $name ) {
			$this->settings['name'] = $name;

			return $this;
		}

		/**
		 * Set relation From with post type
		 *
		 * @param string|PostTypeAbstract $post_type
		 *
		 * @return $this
		 * @throws Exception
		 *
		 * @access public
		 */
		public function setFrom( $post_type ) {
			if ( is_string( $post_type ) ) {
				$this->settings[ 'from' ] = $post_type;
			}else if ( $post_type instanceof PostTypeAbstract ) {
				$this->settings[ 'from' ] = $post_type->getName();
			} else {
				throw new Exception(
					Translate::translate( 'The argument $post_type isn\'t instance of \SilverWp\PostType\PostTypeAbstract' )
				);
			}

			return $this;
		}

		/**
		 * Set relation To with post type
		 *
		 * @param PostTypeAbstract $post_type
		 *
		 * @return $this
		 * @throws Exception
		 *
		 * @access public
		 */
		public function setTo( PostTypeAbstract $post_type ) {
			if ( $post_type instanceof PostTypeAbstract ) {
				$this->settings['to'] = $post_type->getName();
			} else {
				throw new Exception(
					Translate::translate( 'The param $post_type isn\'t instance of \SilverWp\PostType\PostTypeAbstract' )
				);
			}

			return $this;
		}

		/**
		 *
		 * @param array $fields
		 *
		 * @return $this
		 * @access public
		 */
		public function setFields( array $fields ) {
			$this->settings['fields'] = $fields;

			return $this;
		}

		/**
		 * When registering a connection type, you can control if and
		 * where the connections metabox shows up in the admin.
		 *
		 * @param string $show      Possible values for the 'show' param:
		 *                          'any' - show admin box everywhere (default)
		 *                          'from' - show the admin column only on the 'from' end
		 *                          'to' - show the admin column only on the 'to' end
		 *                          false - don't show admin box at all
		 * @param string $context   Possible values for the 'context' param:
		 *                          'side' - show admin box in the right column
		 *                          'advanced' - show the admin box under the main content editor
		 *
		 * @access public
		 * @return $this
		 */
		public function setAdminBox( $show, $context ) {
			$this->settings['admin_box'] = array(
				'show'    => $show,
				'context' => $context
			);

			return $this;
		}

		/**
		 * Magic method to set up settings parameters
		 *
		 * @param string $name
		 * @param mixed $value
		 *
		 * @return $this
		 * @access public
		 */
		public function __set( $name, $value ) {
			$this->settings[ $name ] = $value;

			return $this;
		}

		/**
		 * Set labels to relationship from
		 *
		 * @param string $name
		 * @param string $text
		 *
		 * @return $this
		 * @access public
		 */
		public function setFromLabel( $name, $text ) {
			$this->settings['from_labels'][ $name ] = $text;

			return $this;
		}

		/**
		 * Set labels to relationship to
		 *
		 * @param string $name
		 * @param string $text
		 *
		 * @return $this
		 * @access public
		 */
		public function setToLabel( $name, $text ) {
			$this->settings['to_labels'][ $name ] = $text;

			return $this;
		}

		/**
		 * Get all settings
		 *
		 * @return array
		 * @access public
		 */
		public function getSettings() {
			return $this->settings;
		}

		/**
		 * Register Posts relationship
		 *
		 * @throws Exception
		 * @access public
		 */
		public function register() {
			if ( function_exists( 'p2p_register_connection_type' ) ) {
				p2p_register_connection_type( $this->settings );
			} else {
				throw new Exception(
					Translate::translate(
						'Post 2 post plugin isn\'t activate. To create post type relationship plugin post 2 post is required.'
					)
				);
			}
		}
	}
}