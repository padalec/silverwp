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
namespace SilverWp\Helper\Control;

use SilverWp\Debug;

if ( ! class_exists( '\SilverWp\Helper\Control\CategoriesCheckboxes' ) ) {

	/**
	 * List of all post categories with checkboxes field
	 *
	 * @category   WordPress
	 * @package    SilverWp
	 * @subpackage Helper\Control
	 * @author     Michal Kalkowski <michal at silversite.pl>
	 * @copyright  SilverSite.pl (c) 2015
	 * @version    0.4
	 */
	class CategoriesCheckboxes extends MultiControlAbstract {
		protected $type = 'checkbox';

		public function __construct( $name ) {
			parent::__construct( $name );
			$categories = get_categories();
			foreach ($categories as $category) {
				$this->addOption( $category->term_id, $category->name );
			}
		}
	}
}