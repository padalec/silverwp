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
use SilverWp\FileSystem;
use SilverWp\View;

/**
 * WPML helper functions
 *
 * @author        Michal Kalkowski <michal at silversite.pl>
 * @version       0.5
 * @category      WordPress
 * @package       SilverWp
 * @subpackage    Helper
 * @copyright     2009 - 2015 (c) SilverSite.pl
 */
class Wpml {
	/**
	 * WPML lang switcher
	 *
	 * @static
	 * @access public
	 *
	 * @param string $view_file
	 *
	 * @return string
	 * @throws \SilverWp\Exception
	 */
	public static function langSwitcher( $view_file = 'lang-symbol-switcher' ) {
		if ( function_exists( 'icl_get_languages' ) ) {
			$args      = 'skip_missing=1&orderby=code&order=ASC&link_empty_to=str';
			$languages = icl_get_languages( $args );
			$view_path = FileSystem::getDirectory( 'views' );
			$view      = View::getInstance();
			$content = $view->load(
				$view_path . $view_file, array( 'data' => $languages )
			);

			return $content;
		}

		return false;
	}

	/**
	 * Returns the translated object ID(post_type or term) or original if missing
	 *
	 * @param $object_id integer|string|array The ID/s of the objects to check and return
	 * @param $type the object type: post, page, {custom post type name}, nav_menu, nav_menu_item, category, tag etc.
	 * @return string or array of object ids
	 */
	public static function translate_object_id( $object_id, $type ) {

		// if array
		if( is_array( $object_id ) ){
			$translated_object_ids = array();
			foreach ( $object_id as $id ) {
				$translated_object_ids[] = apply_filters( 'wpml_object_id', $id, $type, true, $icl_get_current_language );
			}
			return $translated_object_ids;
		}
		// if string
		elseif( is_string( $object_id ) ) {
			// check if we have a comma separated ID string
			$is_comma_separated = strpos( $object_id,"," );

			if( $is_comma_separated !== FALSE ) {
				// explode the comma to create an array of IDs
				$object_id     = explode( ',', $object_id );

				$translated_object_ids = array();
				foreach ( $object_id as $id ) {
					$translated_object_ids[] = apply_filters ( 'wpml_object_id', $id, $type, true, $icl_get_current_language );
				}

				// make sure the output is a comma separated string (the same way it came in!)
				return implode ( ',', $translated_object_ids );
			}
			// if we don't find a comma in the string then this is a single ID
			else {
				return apply_filters( 'wpml_object_id', intval( $object_id ), $type, true, $icl_get_current_language );
			}
		}
		// if int
		else {
			return apply_filters( 'wpml_object_id', $object_id, $type, true, $icl_get_current_language );
		}
	}
}
