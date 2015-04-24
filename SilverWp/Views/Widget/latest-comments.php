<?php
/*
 * Copyright (C) 2014 Michal Kalkowski <michal at silversite.pl>
 *
 * SilverWp is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * SilverWp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
 /*
  Repository path: $HeadURL: $
  Last committed: $Revision: $
  Last changed by: $Author: $
  Last changed date: $Date: $
  ID: $Id: $
 */
echo $args['before_widget']; // <section>

echo $args['before_title'] . $instance['title'] . $args['after_title']; // <h3>title</h3>
?>
<i class="icon-quote bg-icon"></i>
<?php
if ( isset( $data[ 'comments_list' ] ) && count( $data[ 'comments_list' ] ) ):
    foreach ( $data[ 'comments_list' ] as $comment ):
        ?>
        <article class="comment">
            <p class="comment-excerpt"><?php echo $comment->comment_content_excerpt ?></p>
            <div class="comment-author"><span class="highlight"><?php echo $comment->comment_author ?></span></div>
            <h6 class="post-title"><a href="<?php echo $comment->post_permalink ?>"><?php echo $comment->post_title ?></a></h6>
        </article>
        <?php
    endforeach;
endif;

echo $args['after_widget'];  // </section>