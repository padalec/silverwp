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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Breadcrumb.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: Breadcrumb.php 2184 2015-01-21 12:20:08Z padalec $
 */

namespace SilverWp\Helper;

use SilverWp\Translate;

/**
 * Breadcrumb usetd to create foot pathes
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: Breadcrumb.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
class Breadcrumb
{
    private $_show_on_home = true;
    private $_delimiter = '';
    /**
     *
     * show current post/page title in breadcrumbs, 0 - don't show
     *
     * @var boolean
     */
    private $_show_current = true;
    /**
     *
     * tag before the current crumb
     *
     * @var string
     */
    private $_before = '<li class="active">';
    /**
     *
     * tag after the current crumb
     *
     * @var string
     */
    private $_after = '</li>';
    /**
     *
     * home page
     *
     * @return string
     */
    public function get_home()
    {
        return home_url();
    }
    public function get_category()
    {
        $categories = array();
        $this_category = get_category( get_query_var('cat'), false );
        if ( $this_category->parent != 0 ) {
            $parent = get_category_parents( $this_category->parent, false, ' ' . $this->_delimiter . ' ' );
        }
        $test = get_the_category_list();
        //'Archive by category "' . single_cat_title('', false) . '"';
        SilverWp_debug_var($this_category);
        SilverWp_debug_var($parent);
        SilverWp_debug_var($test);
    }
    /**
     *
     * get breadcrumbs of pages
     *
     * @param mixed $pages integer, object or array
     * @return array
     */
    public function get_page_path($pages)
    {
        $breadcrumbs = array();
        if( is_object( $pages ) || is_array( $pages ) ) {

            $breadcrumbs[$pages[0]->ID]['url']    = get_permalink( $pages[0]->ID );
            $breadcrumbs[$pages[0]->ID]['title']   = get_the_title( $pages[0]->ID );

        }else{
            while ( $pages ) {
                $page_obj = get_page( $pages );
                $breadcrumbs[ $page_obj->ID ]['url'] = get_permalink( $page_obj->ID );
                $breadcrumbs[ $page_obj->ID ]['title'] = get_the_title( $page_obj->ID );
                $pages  = $page_obj->post_parent;
            }
        }
        //revers array and reset keys
        return array_merge( array_reverse( $breadcrumbs ) );
    }
    /**
     *
     * display full page path
     *
     * @param mixed $page integer, object or array
     */
    public function get_the_page_path($page)
    {
        $breadcrumbs = $this->get_page_path( $page );
        $max = count( $breadcrumbs );
        for ( $i = 0; $i < $max; $i++ ) {
            echo '<li><a href="' . $breadcrumbs[$i]['url'] . '">' . $breadcrumbs[$i]['title'] . '</a></li>' . "\n";

            if ( $i != count($breadcrumbs)-1 ) {
                echo ' ' . $this->_delimiter . ' ';
            }
        }
    }
    public function get_search()
    {
    }
    public function get_day()
    {
    }
    public function get_month()
    {
    }
    public function get_year()
    {
    }
    public function get_attachment()
    {
    }
    public function get_tag()
    {
    }
    public function get_author()
    {
    }
    public function get_404()
    {
    }
    /**
     * get the specjal page designed for posts
     */
    public function get_blog_page()
    {
        $blog_page = array();
        $post_page_id = get_option( 'page_for_posts' );
        if( !is_null( $post_page_id ) ) {
            $blog_page['url'] = get_page_link( $post_page_id );
            $blog_page['title'] = get_the_title( $post_page_id );
        }
        return $blog_page;
    }
    public function get_the_blog_page()
    {
        $blog_page = $this->get_blog_page();
        echo '<li><a href="' . $blog_page['url'] . '">' . $blog_page['title'] . '</a></li>' . "\n";
    }

    /**
     *
     * foot path
     *
     * @global object $post post object
     * @return string
     * @static
     * @todo change from echo to array, and refactor this method
     */
    public function get_breadcrumbs()
    {
        if( function_exists( 'bcn_display' ) ) {
            echo bcn_display();
        }else{
            global $post;
            
            $home_page_id = \get_option('page_on_front');
            $home_link = \get_home_url();
            $home = \get_the_title($home_page_id);
            //$home_link = $this->get_home();
            //$home = Translate::translate( 'home' );
            if ( is_home() || is_front_page() ) {
                
                echo '<ol class="breadcrumb">';
                if ( $this->_show_on_home ) {
                    echo '<li><a href="' . $home_link . '">' . $home . '</a></li>' . $this->_delimiter . ' ' . "\n";
                }
                $blog_page = $this->get_blog_page();
                if( isset( $blog_page['title'] ) ) {
                    echo '<li class="active">' . $blog_page['title'] . '</li>' . "\n";
                }
                echo '</ol>' . "\n";
            } else {
                echo '<ol class="breadcrumb"><li><a href="' . $home_link . '">' . $home . '</a></li>' . $this->_delimiter . ' ' . "\n";

                if ( is_category() ) {
                    $this->get_blog_page();
                    $thisCat = get_category( get_query_var('cat'), false );
                    if ($thisCat->parent != 0) {
                        echo '<li>' . get_category_parents( $thisCat->parent, true, ' </li><li> ' ) . '</li>' . "\n";
                    }
                    echo $this->_before . Translate::translate( 'Archive by category' ) .' "' . single_cat_title('', false) . '"' . $this->_after;

                } elseif ( is_search() ) {
                    echo $this->_before . Translate::translate( 'Search' ) . '"' . get_search_query() . '"' . $this->_after;

                } elseif ( is_day() ) {
                    echo '<li><a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a></li>' . $this->_delimiter . ' ' . "\n";
                    echo '<li><a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '">' . get_the_time( 'F' ) . '</a></li>' . $this->_delimiter . ' ' . "\n";
                    echo $this->_before . get_the_time( 'd' ) . $this->_after;

                } elseif ( is_month() ) {
                    echo '<li><a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a></li>' . $this->_delimiter . ' ' . "\n";
                    echo $this->_before . get_the_time( 'F' ) . $this->_after;

                } elseif ( is_year() ) {
                    echo $this->_before . get_the_time( 'Y' ) . $this->_after;

                } elseif ( is_single() && !is_attachment() ) {
                    //custom post types
                    $post_type = get_post_type();
                    if ( $post_type != 'post' ) {
                        $page_object = \SilverWp\Helper\Page::getPagesByTemplates( $post_type );
                        //if page temaplate for page lists is set get page_id
                        if( isset( $page_object[0]->ID ) ){
                            $page_id = $page_object[0]->ID;
                        }else{
                            //if not return home id
                            $page_id = get_option( 'page_on_front' );
                        }

                        $this->get_the_page_path( $page_id );

                        if ( $this->_show_current == 1 ) {
                            echo ' ' . $this->_delimiter . ' ' . $this->_before . get_the_title() . $this->_after . "\n";
                        }
                    } else {
                        $cat = get_the_category();
                        $cat_string = '<li>' . get_category_parents( $cat[0]->cat_ID, true, '</li><li>' ) . '</li>';
                        echo substr( $cat_string, 0, strlen( $cat_string ) - strlen( '</li><li>' ) ) . "\n" ;
                        if ( $this->_show_current == 1 ) {
                            echo $this->_before . get_the_title() . $this->_after;
                        }
                    }

                } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
                    $post_type = get_post_type_object(get_post_type());
                    echo $this->_before . $post_type->labels->singular_name . $this->_after;

                } elseif ( is_attachment() ) {

                    if( $post->post_parent != 0 ){
                        $parent = get_post( $post->post_parent );
                        $cat = get_the_category( $parent->ID );
                        if( isset( $cat[0] ) ){
                            echo get_category_parents( $cat[0], true, ' ' . $this->_delimiter . ' ' );
                        }
                        echo '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a></li>' . "\n";
                    }
                    if ( $this->_show_current == 1 ) {
                        echo ' ' . $this->_delimiter . ' ' . $this->_before . get_the_title() . $this->_after;
                    }

                } elseif ( is_page() && !$post->post_parent ) {

                    if ($this->_show_current == 1) {
                        echo $this->_before . get_the_title() . $this->_after;
                    }

                } elseif ( is_page() && $post->post_parent ) {
                    $parent_id  = $post->post_parent;

                    $this->get_the_page_path( $parent_id );
                    if ( $this->_show_current == 1 ) {
                        echo ' ' . $this->_delimiter . ' ' . $this->_before . get_the_title() . $this->_after;
                    }

                } elseif ( is_tag() ) {
                    echo $this->_before . Translate::translate( 'Posts tagged' ) . ' "' . single_tag_title('', false) . '"' . $this->_after;
                } elseif ( is_author() ) {
                    global $author;
                    $userdata = get_userdata( $author );
                    echo $this->_before . Translate::translate( 'Articles posted by' ) . ' ' . $userdata->display_name . $this->_after;

                } elseif ( is_404() ) {
                    echo $this->_before . Translate::translate( 'Error 404' ) . $this->_after;
                }

                if ( get_query_var('paged') ) {
                    /*if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
                        
                    }*/
                    //echo $this->_before;
                    echo '<span class="active"> (';
                    echo Translate::translate('Page') . ' ' . \get_query_var('paged');
                    echo ')</span>';
                    //echo $this->_after;
                    /*if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
                        
                    }*/
                }

                echo '</ol>' . "\n";

            }
        }
    } // end qt_custom_breadcrumbs()
    public function get_term_list($post_id, $taxonomy)
    {
        $terms_tree = array();
        $args = array(
            'hide_empty'    => false,
            'hierarchical'  => true
        );
        $terms = get_terms( $taxonomy, $args );
        foreach( $terms as $key => $term ){
            $terms_tree[$key] = array(
                'term_id' => $term->term_id,
                'name'    => $term->name,
                'slug'    => $term->slug,
                //'child'   => get_term_children( $term->term_id, $taxonomy )
            );
        }
        return $terms_tree;
    }
}
