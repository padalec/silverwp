<?php

/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Theme.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: Theme.php 2184 2015-01-21 12:20:08Z padalec $
 */

namespace SilverWp\Helper;

/**
 * Theme data
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: Theme.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
class Theme {

    /**
     *
     * Gets a WP_Theme object for a theme
     *
     * @param string $value (Optional) name of theme info we whant to get. Default: null
     * @param string $stylesheet (Optional) Directory name for the theme. Defaults to current theme. Default: Null
     *
     * @return mixed object or string
     * @link http://codex.wordpress.org/Function_Reference/wp_get_theme full description about this function
     */
    public static function getThemeInfo( $value = null, $stylesheet = null ) {
        $theme_data = wp_get_theme( $stylesheet );

        if ( is_child_theme() ) {
            $theme_data = wp_get_theme( $theme_data->get( 'Template' ) );
        }
        $theme_info = is_null( $value ) ? $theme_data : $theme_data->get( $value );
        return $theme_info;
    }
}
