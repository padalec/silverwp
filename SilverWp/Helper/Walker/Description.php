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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Walker/Description.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: Description.php 2184 2015-01-21 12:20:08Z padalec $
 */

namespace SilverWp\Helper\Walker;

/**
 * Menu walker for one page menu
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: Description.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage Helper\Walker
 * @link http://www.themevan.com/build-an-one-page-portfolio-website-with-wordpress/ tutorial
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
class Description extends \Walker_Nav_Menu
{
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $class_names = $value = '';
        $classes = empty($item->classes) ? array() : (array)$item->classes;
        $class_names = \join(' ', \apply_filters('nav_menu_css_class', \array_filter($classes), $item));
        $class_names = ' class="'. \esc_attr($class_names) . '"';
        
        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
        
        $attributes  = ! empty($item->attr_title) ? ' title="'  . \esc_attr($item->attr_title) . '"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . \esc_attr($item->target) . '"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . \esc_attr($item->xfn) . '"' : '';
        if ($item->object == 'page') {
            $varpost = \get_post($item->object_id);
            if (\is_home()) {
                $attributes .= ' href="#' . $varpost->post_name . '"';
            } else {
                $attributes .= ' href="' . \home_url() . '/#' . $varpost->post_name . '"';
            }
        } else {
            $attributes .= ! empty($item->url) ? ' href="' . \esc_attr($item->url) . '"' : '';
        }
        $item_output  = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . \apply_filters('the_title', $item->title, $item->ID);
        $item_output .= $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        $output .= \apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}
