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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Paginator/Pager.php $
  Last committed: $Revision: 2313 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-02-02 16:44:30 +0100 (Pn, 02 lut 2015) $
  ID: $Id: Pager.php 2313 2015-02-02 15:44:30Z padalec $
 */

namespace SilverWp\Helper\Paginator;

/**
 * Paginator
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: Pager.php 2313 2015-02-02 15:44:30Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */

class Pager implements PaginatorInterface {
    /**
     * The current page number. Default: 0
     *
     * @var integer
     * @access protected
     */
    protected $max_num_pages = 0;
    /**
     * The total amount of pages. Default: 1
     *
     * @var integer
     * @access protected
     */
    protected $total_posts = 1;

    /**
     *
     * class constructor
     *
     * @access public
     */
    public function __construct() {
        //$this->total_posts = $totla_page;
        //$this->max_num_pages = $max_num_pages;
        add_filter( 'paginate_links', array( $this, 'fixUrl' ) );
    }

    /**
     * Fix for url because I don't know way but in url & char is changed to #038
     * http://localhost/igniter/?page_id=22#038;paged=2
     *
     * @param $link
     *
     * @return mixed
     * @access
     */
    public function fixUrl( $link ) {
        return str_replace( '#038;', '&', $link );
    }
    /**
     *
     * set total page limit
     *
     * @param integer $total_posts
     *
     * @access public
     */
    public function setTotalPosts( $total_posts ) {
        $this->total_posts = $total_posts;
    }

    /**
     *
     * set current page
     *
     * @param integer $max_num_pages
     *
     * @access public
     */
    public function setMaxNumPages( $max_num_pages ) {
        $this->max_num_pages = $max_num_pages;
    }

    /**
     *
     * this method display all links for pagintaro
     *
     * @link http://codex.wordpress.org/Function_Reference/paginate_links
     *
     * @param array $args - array wiyh
     *
     * @return array list of all links
     */
    public function getLinks() {
        $big  = 999999999;
        $base = str_replace( $big, '%#%', get_pagenum_link( $big ) );

        if ( get_option( 'permalink_structure' ) ) {
            $format = '/page/%#%';
        } else {
            $format = '?page=%#%';
        }

        $prev_arrow = is_rtl() ? '&rarr;' : '<i class="icon-left-open-big"></i>';
        $next_arrow = is_rtl() ? '&larr;' : '<i class="icon-right-open-big"></i>';

        $params    = array(
            'base'      => $base,
            'format'    => $format,
            'total'     => $this->total_posts,
            'current'   => max( 1, $this->max_num_pages ),
            'show_all'  => false,
            'end_size'  => 1,
            'mid_size'  => 2,
            'prev_next' => true,
            'prev_text' => $prev_arrow,
            'next_text' => $next_arrow,
            'type'      => 'array',
            //'add_args'     => false,
            //'add_fragment' => ''
            //'before_page_number' => '',
            //'after_page_number' => ''
        );

        $pager = \paginate_links( $params );

        return $pager;
    }

    /**
     *
     * get total page count
     *
     * @return integer
     * @access public
     */
    public function getTotalPosts() {
        return $this->total_posts;
    }

    /**
     * get current page
     *
     * @return integer
     * @access public
     */
    public function getMaxNumPages() {
        return $this->max_num_pages;
    }
}