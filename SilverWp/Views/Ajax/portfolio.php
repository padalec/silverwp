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
$category_bool = \SilverWp\Helper\Option::get_theme_option('portfolio_list_category') === '1';
$tag_bool = \SilverWp\Helper\Option::get_theme_option('portfolio_list_tag') === '1';
$return = '';
if (count($data) !== 0) {
    foreach ($data as $item) {
        $return .= '<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6  item  hide-item">';
        $return .= '    <figure class="bg-brandcolour hover-effect">';
        $return .=          $item['image_html'];
        $return .= '        <figcaption>';
        $return .= '            <h3 class="container-vertical-center name">'.$item['title'].'</h3>';
        $return .= $tag_bool || $category_bool ? '<p class="container-vertical-center">' : '';
        if ($tag_bool && count($item['tags']) !== 0) {
            $return .= '            <i class="klico-tag"></i>';
            foreach ($item['tags'] as $k => $tag) {
                if ($k !== 0) {
                    $return .= ', ';
                }
                $return .= $tag['name'];
            }
        }
        if ($category_bool && count($item['category']) !== 0) {
            $return .= '           <i class="klico-page"></i>';
            foreach ($item['category'] as $k => $aCategory) {
                if ($k !== 0) {
                    $return .= ', ';
                }
                $return .= $aCategory['name'];
            }
        }
        $return .= $tag_bool || $category_bool ? '</p>' : '';
        $return .= '            <a href="'.$item['link'].'">view more</a>';
        $return .= '        </figcaption>';
        $return .= '    </figure>';
        $return .= '   </div>'."\n"; // item
    }
}
/* - rejected idea
if (isset($pager)) {
    $return .= '<ul class="pagination">';
    foreach($pager as $p_item) {
        $return .= '<li>'.$p_item.'</li>';
    }
    $return .= '</ul>';
}
var_dump($pager);
*/
echo $return;
//silverwp_debug_array($data);