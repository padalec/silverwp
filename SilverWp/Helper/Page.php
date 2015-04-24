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

use SilverWp\PostType\PostTypeAbstract;

/**
 * Page helpers
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Revision: 2338 $
 * @category WordPress
 * @package Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */

class Page {
    /**
     *
     * get list of pages where page template is assigned
     *
     * @param string $post_type - post type name
     *
     * @return array
     */
    public static function getPagesByTemplates( $post_type ) {
        $pages_object = array();
        $templates    = PostTypeAbstract::getTemplates( $post_type );
        $pages = self::getPageByTemplate( $templates );
        foreach ( $pages as $page ) {
            $pages_object[ ] = $page;
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
     */
    public static function getPageByTemplate( array $template_name ) {
        $posts = get_posts(
            array(
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
            )
        );
        return $posts;
    }

    /**
     *
     * Get page id where post type is assigned
     *
     * @param string $post_type post type name
     *
     * @return integer
     */
    public static function getIdByPostType( $post_type ) {
        $page_object = self::getPagesByTemplates( $post_type );

        return $page_object[ 0 ]->ID;
    }
}
