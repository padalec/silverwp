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

/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/View.php $
  Last committed: $Revision: 2182 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:00:49 +0100 (Śr, 21 sty 2015) $
  ID: $Id: View.php 2182 2015-01-21 12:00:49Z padalec $
 */

namespace SilverWp;

use SilverWp\Ajax\AjaxAbstract;
use VP_FileSystem;

/**
 * View file renderer
 *
 * @author        Michal Kalkowski <michal at silversite.pl>
 * @version       $Id: View.php 2182 2015-01-21 12:00:49Z padalec $
 * @category      WordPress
 * @package       SilverWp
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
class View extends SingletonAbstract {
	protected function __construct() {

	}

	/**
	 * Load view file
	 *
	 * @param  string $file Name of the view file
	 * @param  array  $data Array of data to be bind on the view
	 *
	 * @throws \SilverWp\Exception
	 * @return String The result view
	 * @access public
	 */
	public function load( $file, $data = array(), $extension = 'php' ) {
		$view_file = $file . '.' . $extension;

		if ( ! file_exists( $view_file ) ) {
			throw new Exception( "View file not found: $view_file" );
		}
		\extract( $data );
		// fix bug when data is form Ajaxt request
		if ( AjaxAbstract::isAjax() ) {
			return include $view_file;
		} else {
			\ob_start();
			include $view_file;
			$content = \ob_get_clean();

			return $content;
		}
	}

	/**
	 * Render view
	 *
	 * @param string $view_file path to file important: without extension
	 * @param array  $data      data to load to theme
	 *
	 * @static
	 * @access public
	 */
	public static function render( $view_file, array $data ) {
		try {
			$view = View::getInstance()->load( $view_file, $data );

			return $view;
		} catch ( Exception $ex ) {
			echo $ex->displayAdminNotice();
		}
	}
}
