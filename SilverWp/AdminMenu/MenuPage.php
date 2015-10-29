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

namespace SilverWp\AdminMenu;

use SilverWp\SingletonAbstract;
use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\AdminMenu\MenuPage' ) ) {
	/**
	 * Add menu or sub menu page
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       0.5
	 * @category      WordPress
	 * @package       SilverWp
	 * @subpackage    AdminMenu
	 * @copyright     2009 - 2015 (c) SilverSite.pl
	 */
	class MenuPage implements MenuPageInterface {
		/**
		 * @var string
		 */
		protected $capability = 'edit_posts';
		/**
		 * @var
		 */
		protected $slug;
		/**
		 * @var
		 */
		protected $callback;
		/**
		 * @var string
		 */
		private $icon = '';
		/**
		 * @var int
		 */
		private $position = 80;
		/**
		 * @var
		 */
		protected $page_title;
		/**
		 * @var
		 */
		protected $menu_title;

		/**
		 * Class constructor
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'registerMenuPage' ) );
		}

		/**
		 * @param string $url
		 *
		 * @return MenuPage
		 * @access public
		 */
		public function setIcon( $url ) {
			$this->icon = $url;

			return $this;
		}

		/**
		 * @param $slug
		 *
		 * @return MenuPage
		 * @access public
		 */
		public function setSlug( $slug ) {
			$this->slug = $slug;

			return $this;
		}

		/**
		 *
		 * @param array|string $function
		 *
		 * @return $this
		 * @access public
		 */
		public function setCallback( $function ) {
			$this->callback = $function;

			return $this;
		}

		/**
		 * @param string $capability
		 *
		 * @return MenuPage
		 */
		public function setCapability( $capability ) {
			$this->capability = $capability;

			return $this;
		}

		/**
		 * @param int $position
		 *
		 * @return MenuPage
		 */
		public function setPosition( $position ) {
			$this->position = $position;

			return $this;
		}

		/**
		 * @param string $page_title
		 *
		 * @return MenuPage
		 */
		public function setPageTitle( $page_title ) {
			$this->page_title = $page_title;

			return $this;
		}

		/**
		 * @param string $menu_title
		 *
		 * @return MenuPage
		 */
		public function setMenuTitle( $menu_title ) {
			$this->menu_title = $menu_title;

			return $this;
		}

		/**
		 * @return string
		 * @
		 * @access
		 */
		public function getSlug() {
			if ( isset( $this->slug ) && ! empty( $this->slug ) ) {
				return $this->slug;
			} else {
				return sanitize_title( $this->page_title );
			}
		}


		/**
		 * Register menu page
		 *
		 * @throws Exception
		 * @access public
		 */
		public function registerMenuPage() {
			if ( ! isset( $this->page_title ) ) {
				throw new Exception(
					Translate::translate(
						'Property: ' . __CLASS__
						. '::page_title is required and can\'t be empty.'
					)
				);
			}
			if ( ! isset( $this->menu_title ) ) {
				throw new Exception(
					Translate::translate(
						'Property: ' . __CLASS__
						. '::menu_title is required and can\'t be empty.'
					)
				);
			}
			if ( ! isset( $this->capability ) ) {
				throw new Exception(
					Translate::translate(
						'Property: ' . __CLASS__
						. '::capability is required and can\'t be empty.'
					)
				);
			}
			add_menu_page(
				$this->page_title,
				$this->menu_title,
				$this->capability,
				$this->getSlug(),
				$this->callback,
				$this->icon,
				$this->position
			);
		}
	}
}