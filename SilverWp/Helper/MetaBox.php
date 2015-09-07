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

use SilverWp\Debug;
use SilverWp\FileSystem;

/**
 * MetaBox helper
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: MetaBox.php 2415 2015-02-11 13:49:13Z padalec $
 * @category Wordress
 * @package SilverWp
 * @subpackage Helper
 * @copyright (c) 2014, SilverSite.pl
 */
class MetaBox {
    /**
     *
     * create meta box name
     *
     * @param string $post_type
     *
     * @return string
     */
    public static function getKeyName( $post_type ) {
        return THEME_OPTION_PREFIX . '_' . $post_type;
    }

    /**
     *
     * get post meta data
     *
     * @param string  $key
     * @param string  $field_name
     * @param boolean $remove_first if array have only one element and if should be removed set to true
     *
     * @return mixed array, string or false if not found
     */
    public static function getPostMeta( $key, $field_name, $post_id = null, $remove_first = true ) {
        $key       = self::getKeyName( $key );
        $post_meta = \get_post_meta( $post_id, $key, true );

        if ( $post_meta && RecursiveArray::searchKey( $field_name, $post_meta ) ) {

            $matches = RecursiveArray::searchRecursive( $post_meta, $field_name );

            if ( \count( $matches ) == 1 && \is_array( $matches ) && $remove_first ) {
                return $matches[ 0 ];
            }

            return $matches;

        } else {
            return false;
        }
    }

    /**
     *
     * filter $_POST meta data options and prepare to save in db
     *
     * @param array  $options array with meta data to filter
     * @param string $prefix
     *
     * @return array
     */
    public static function getPostOption( array $options, $prefix = null ) {
        if ( ! \is_null( $prefix ) ) {
            $prefix = '_' . $prefix . '_';
        }
        $option = array();
        foreach ( $options as $key => $value ) {
            $result = array();
            if ( \preg_match( '/^' . THEME_OPTION_PREFIX . $prefix . '/', $key, $result ) ) {
                $index = \preg_replace( '/^' . THEME_OPTION_PREFIX . '_/', '', $key );
                // Sanitize the user input.
                $option[ $index ] = String::sanitize_text_field( $value );
            }
        }

        return $option;
    }

    /**
     *
     * update or insert if not exists post meta
     * alias updatePostMeta
     *
     * @link http://codex.wordpress.org/Function_Reference/updatePostMeta references
     *
     * @param int   $post_id Post ID
     * @param array $data meta data
     *
     * @return boolean true on sucess false on failur
     */
    public static function updatePostMeta( $post_id, array $data ) {
        $post_type = \get_post_type();

        return \update_post_meta( $post_id, self::getKeyName( $post_type ), $data );
    }

    /**
     *
     * Get list of icons (default: Fontello)
     *
     * @param string $name of css class
     * @param string $path path to css file with fonts
     *
     * @param string $transient_name cache key name
     *
     * @return array
     * @static
     */
    public static function getFontelloIcons( $name = 'icon', $path = null, $transient_name = 'silverwp_fontello' ) {
        if ( \is_null( $path ) ) {
            $fonts_path = FileSystem::getDirectory( 'fonts_path' );
            $path        = $fonts_path . 'fontello.css';
        }

        if ( ( $icons = \get_transient( $transient_name ) ) == false ) {
            if ( ! file_exists( $path ) ) {
                return false;
            }
            $matches = array();

            $pattern = '/\.(' . $name . '-(?:\w+(?:-)?)+):before\s*{\s*content/';
            $subject = \file_get_contents( $path );

            \preg_match_all( $pattern, $subject, $matches, PREG_SET_ORDER );

            foreach ( $matches as $match ) {
                $icons[ ] = array(
                    'value' => $match[ 1 ],
                    'label' => str_replace( $name . '-', '', $match[ 1 ] ),
                );
            }
            \set_transient( $transient_name, $icons, 60 * 60 * 24 );
        }

        return $icons;
    }

    /**
     *
     * check the current post type have sidebar
     *
     * @param string $post_type post type name
     * @param int    $post_id post id
     *
     * @return boolean
     * @access public
     * @static
     */
    public static function isSidebar( $post_type, $post_id ) {
        $sidebar    = self::getPostMeta( $post_type, 'sidebar', $post_id );
        $is_sidebar = ( ! $sidebar ) ? false : true;

        return $is_sidebar;
    }
}
