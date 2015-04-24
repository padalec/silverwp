<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/PostType.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: PostType.php 2184 2015-01-21 12:20:08Z padalec $
 */

/**
 * Helper functions for PostType
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: PostType.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */

namespace SilverWp\Helper;
//use SilverWp\Translate;

class PostType {
    private static $_default_post_types = array(
        'post','page','attachment','revision','nav_menu_item'
    );
    /**
     * check if post type exists and is registered
     * @param string $post_type
     * @return boolean
     */
    public static function is_registered( $post_type ) {
        return post_type_exists( $post_type );
    }
    /**
     * get all registered post types
     * @return array
     */
    public static function get_all_registered() {
        return array_keys( get_post_types( '', 'names' ) );
    }
    /**
     * get custom registered post types
     * 
     * @return array
     * @static
     */
    public static function get_custom_post_type(){
        $all_pt = self::get_all_registered();
        $custom_pt = array_diff_assoc( $all_pt, self::$_default_post_types );
        return array_merge($custom_pt);
    }
}
