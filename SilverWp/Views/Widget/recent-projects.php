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

echo $args['before_widget']; // <section>

echo $args['before_title'] . $instance['title'] . $args['after_title']; // <h3>title</h3>

$layout = false ? 'grid' : 'list';

if ($layout === 'list'):
?>
<ul class="wrapper-list">
<?php foreach($data as $item): ?>
    <li>
        <a href="<?php echo $item['link'] ?>" rel="bookmark" class="fade-hover-effect">
            <div class="row">
                <div class="col-xs-3 col-img">
                    <?php echo $item['image_html'] ?>
                    <span class="hover-pattern bg-brandcolour"><i class="fa fa-plus container-center" style="position:absolute"></i></span>
                </div>
                <div class="col-xs-9">
                    <strong><?php echo $item['title'] ?></strong><br />
                    <?php 
                    if (\SilverWp\Helper\Option::get_theme_option('portfolio_list_category') === '1') {
                        foreach ($item['category'] as $key => $category) {
                            echo $key === 0 ? '' : ', '; // separateor
                            echo $category['name'];
                        }
                    }
                    elseif (\SilverWp\Helper\Option::get_theme_option('portfolio_list_tag') === '1') {
                        foreach ($item['tags'] as $key => $category) {
                            echo $key === 0 ? '' : ', '; // separateor
                            echo $category['name'];
                        }                        
                    }
                    ?>
                </div>
            </div>
        </a>
    </li>
<?php endforeach; ?>
</ul>
<?php
elseif ($layout === 'grid'):
?>
<div class="wrapper-grid">
<?php foreach($data as $item): ?>
    <figure class="hover-effect">
        <?php echo $item['image_html'] ?>
        <figcaption>
            <div class="bg-brandcolour hover-pattern"></div>
            <div class="container-vertical-center fade-element"><i class="fa fa-plus"></i></div>
            <a class="more" href="<?php echo $item['link'] ?>" rel="bookmark"><?php echo $item['title'] ?></a>
        </figcaption>
    </figure>
<?php endforeach; ?>
</div>
<?php
endif;

echo $args['after_widget'];  // </section>

//silverwp_debug_array($data);
//silverwp_debug_array($args);
//silverwp_debug_array($instance);
