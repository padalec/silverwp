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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Page.php $
  Last committed: $Revision: 2338 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-02-04 15:16:58 +0100 (Śr, 04 lut 2015) $
  ID: $Id: Page.php 2338 2015-02-04 14:16:58Z padalec $
 */

namespace SilverWp\Helper;

use SilverWp\Debug;
use SilverWp\PostType\PostTypeAbstract;

/**
 * Page helpers
 *
 * @author        Michal Kalkowski <michal at silversite.pl>
 * @version       0.5
 * @category      WordPress
 * @package       Helper
 * @copyright     2009 - 2015, (c) SilverSite.pl
 */
class Page {
	/**
	 *
	 * get list of pages where page template is assigned
	 *
	 * @param string $post_type - post type name
	 *
	 * @return array
	 * @static
	 * @access public
	 */
	public static function getPagesByTemplates( $post_type ) {
		$pages_object = array();
		$templates    = PostTypeAbstract::getTemplates( $post_type );
		$pages        = self::getPageByTemplate( $templates );

		foreach ( $pages as $page ) {
			$pages_object[] = $page;
		}

		return $pages_object;
	}

	/**
	 *
	 * Return page object where $template_name is assigned
	 *
	 * @param array $template_name
	 *
	 * @return object WP_Query
	 * @static
	 * @link http://codex.wordpress.org/Class_Reference/WP_Meta_Query
	 * @access public
	 */
	public static function getPageByTemplate( array $template_name ) {
		$params = array(
			'post_type'  => 'page',
			'orderby'    => 'post_date',
			'order'      => 'DESC',
			'meta_query' => array(
				array(
					'key'     => '_wp_page_template',
					'value'   => $template_name,
					'compare' => 'IN',
				)
			)
		);
		$pages  = get_posts( $params );

		return $pages;
	}

	/**
	 *
	 * Get page id where post type is assigned
	 *
	 * @param string $post_type post type name
	 *
	 * @return integer
	 * @static
	 * @access public
	 */
	public static function getIdByPostType( $post_type ) {
		$page_object = self::getPagesByTemplates( $post_type );

		if ( function_exists( 'icl_object_id' ) ) {
			$page_id = icl_object_id( $page_object[0]->ID, 'page', true, ICL_LANGUAGE_CODE );
		} else {
			$page_id = $page_object[0]->ID;
		}

		return $page_id;
	}
}
