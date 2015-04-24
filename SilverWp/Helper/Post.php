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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Post.php $
  Last committed: $Revision: 2265 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-29 10:52:16 +0100 (Cz, 29 sty 2015) $
  ID: $Id: Post.php 2265 2015-01-29 09:52:16Z padalec $
 */

namespace SilverWp\Helper;

use SilverWp\SingletonAbstract;

/**
 * Blog Post helper
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: Post.php 2265 2015-01-29 09:52:16Z padalec $
 * @category WordPress
 * @package Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */


class Post {

    /**
     * Latest blog posts
     *
     * @param int    $limit post display limit
     *
     * @param string $thumbnail_size
     *
     * @return array
     */
    public static function getRecent($limit = 10, $thumbnail_size = 'thumbnail') {

        $args = array(
            'numberposts'      => $limit,
            'offset'           => 0,
            'category'         => 0,
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'post',
            'post_status'      => 'draft, publish, future, pending',//'draft, publish, future, pending, private',
            'suppress_filters' => true
        );

        $array_out = array();
        $recent_posts = \get_posts( \wp_parse_args( $args ) );

        /*$like_bool    = Option::get_theme_option( 'blog_list_like' );
        if ( $like_bool === '1' ) {
            $PostLike = \SilverWp\Ajax\PostLike::getInstance();
        }*/
        foreach ( $recent_posts as $key => $recent ) {
            \setup_postdata( $recent );
            $post_id = $recent->ID;
            //$array_out[ $key ] = $recent;
            $array_out[ $key ][ 'ID' ]            = $post_id;
            $array_out[ $key ][ 'post_title' ]    = \get_the_title( $post_id );
            $array_out[ $key ][ 'url' ]           = \get_the_permalink( $post_id );
            $array_out[ $key ][ 'post_author' ]   = \get_the_author();
            $array_out[ $key ][ 'post_date' ]     = \get_the_date( '', $post_id );
            $array_out[ $key ][ 'post_date_utc' ] = \get_the_time( 'c', $post_id );

            //$array_out[ $key ]['post_like'] = ($like_bool === '1') ? $PostLike->getPostLikeCount($post_id) : '';
            $array_out[ $key ][ 'post_comment_count' ]     = $recent->comment_count;

            if ( strpos( $recent->post_content, '<!--more-->' ) || empty( $recent->post_excerpt ) ) {
                $array_out[ $key ][ 'post_excerpt' ] = \get_the_excerpt();
            } else {
                $array_out[ $key ][ 'post_excerpt' ] = $recent->post_excerpt;
            }

            $array_out[ $key ][ 'image_html' ] = \get_the_post_thumbnail( $post_id, $thumbnail_size );// Thumbnail
            $array_out[ $key ][ 'categories' ] = self::getTaxonomy( $post_id );

        }
        \wp_reset_postdata();

        return $array_out;
    }

    /**
     * Get post taxonomy
     *
     * @param int    $post_id
     *
     * @param string $tax_name
     *
     * @return array
     * @static
     * @access public
     */
    public static function getTaxonomy( $post_id, $tax_name = 'category' ) {
        $query_args = array(
            'orderby'   => 'name',
            'order'     => 'ASC',
            'fields'    => 'all',
        );

        $terms = \wp_get_object_terms( (int) $post_id, $tax_name, $query_args );
        $terms_array = UtlArray::object_to_array( $terms );
        return $terms_array;
    }
    /**
     *
     * count all published posts from given type
     *
     * @param string $post_type Post type to count Default: 'post'
     *
     * @return int
     */
    public static function getPublishCount( $post_type = 'post' ) {
        return (int) \wp_count_posts( $post_type )->publish;
    }

}
