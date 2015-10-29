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

if ( ! class_exists( 'SilverWp\AdminMenu\SubMenuPage' ) ) {
	/**
	 * Add sub menu page
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       0.1
	 * @category      WordPress
	 * @package       SilverWp
	 * @subpackage    AdminMenu
	 * @copyright     2009 - 2015 (c) SilverSite.pl
	 * @since         0.5
	 */
	class SubMenuPage extends MenuPage implements MenuPageInterface {
		/**
		 * @var string
		 * @access private
		 */
		private $parent_slug;

		/**
		 * @param string $parent_slug
		 *
		 * @return SubMenuPage
		 */
		public function setParentSlug( $parent_slug ) {
			$this->parent_slug = $parent_slug;

			return $this;
		}

		/**
		 * Register menu page
		 *
		 * @throws Exception
		 * @access public
		 */
		public function registerMenuPage() {
			if ( ! isset( $this->parent_slug ) ) {
				throw new Exception(
					Translate::translate(
						'Property: ' . __CLASS__
						. '::parent_slug is required and can\'t be empty.'
					)
				);
			}
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

			add_submenu_page(
				$this->parent_slug,
				$this->page_title,
				$this->menu_title,
				$this->capability,
				$this->getSlug(),
				$this->callback
			);
		}
	}
}