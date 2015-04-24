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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Social.php $
  Last committed: $Revision: 2358 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-02-06 12:44:28 +0100 (Pt, 06 lut 2015) $
  ID: $Id: Social.php 2358 2015-02-06 11:44:28Z padalec $
 */

namespace SilverWp\Helper;

/**
 * Social media class
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: Social.php 2358 2015-02-06 11:44:28Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
class Social {
    /**
     * get plugin sorted list
     *
     * @return array
     * @static
     */
    public static function getPlugins() {
        $providers = silverwp_get_social_plugins();
        $fields    = array(
            'show' => 'plugin_social_show',
        );
        $return    = Option::option_sort_by_order( $providers, $fields, 'plugin_social_order' );

        return $return;
    }

    /**
     * List off all enabled share buttons
     *
     * @param array $share_params this parameter will be replaced in share url
     *
     * @return array
     * @static
     * @access public
     */
    public static function getShareButtons( array $share_params ) {
        $providers = silverwp_get_social_providers();
        $buttons   = array();
        foreach ( $providers as $key => $provider ) {
            if ( $provider[ 'share_url' ] != '' && $provider[ 'icon' ] != '' ) {
                $slug = \sanitize_title( $provider[ 'name' ] );
                $option_key  = 'social_share_providers[' . $slug . ']';
                $provider_on = Option::get_theme_option( $option_key );
                if ( $provider_on == '1' ) {
                    $buttons[ $key ]                = $provider;
                    $buttons[ $key ][ 'share_url' ] = String::str_replace_from_array(
                        $share_params,
                        $provider[ 'share_url' ]
                    );
                    $buttons[ $key ][ 'slug' ]      = $slug;
                }
            }
        }

        return $buttons;
    }

    /**
     * Get list of all social icons
     *
     * @return array
     * @static
     * @access public
     */
    public static function getIcons() {
        $icons     = array();
        $icons_tmp = \silverwp_get_social_icon();
        $settings  = Option::get_theme_option( 'social_bookmark', true );

        foreach ( $icons_tmp as $icon ) {
            $key = \sanitize_title( $icon[ 'label' ] );
            if ( isset( $settings[ $key ] ) && $settings[ $key ][ 'url' ] != '' ) {
                $icons[ $key ]           = $settings[ $key ];
                $icons[ $key ][ 'icon' ] = $icon[ 'value' ];
            }
        }
        //sort array by order field
        UtlArray::array_sort_by_column( $icons, 'order', \SORT_ASC );

        return $icons;
    }

    /**
     * List of all configured in theme option social accounts
     *
     * @return array
     * @static
     * @access public
     */
    public static function getAccounts() {
        $accounts_list  = Option::get_theme_option( 'social_accounts', true );
        $provider_list  = silverwp_get_social_providers();
        $social = array();
        foreach ( $provider_list as $provider ) {
            foreach ( $accounts_list as $slug => $value ) {
                if ( sanitize_title( $provider[ 'name' ] ) == $slug && ! empty( $value[ 'url' ] ) ) {
                    $social[ ] = array(
                        'name'  => $provider[ 'name' ],
                        'url'   => $value[ 'url' ],
                        'order' => $value[ 'order' ],
                        'icon'  => $provider[ 'icon' ],
                        'slug'  => $slug,
                    );
                }
            }
        }
        UtlArray::array_sort_by_column( $social, 'order' );
        return $social;
    }
}
