<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Db/Query.php $
  Last committed: $Revision: 1572 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2014-10-02 13:22:19 +0200 (Cz, 02 paź 2014) $
  ID: $Id: Query.php 1572 2014-10-02 11:22:19Z padalec $
 */

/**
 * Db query
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: Query.php 1572 2014-10-02 11:22:19Z padalec $
 * @category WordPress
 * @package Db
 * @copyright (c) 2014, SilverSite.pl
 */

namespace SilverWp\Db;

use SilverWp\Helper\RecursiveArray;
use SilverWp\PostType\PostTypeInterface;

class Query extends \WP_Query {
    private $limit;
    private $pagination = false;
    private $post_id = null;
    private $post_type;
    private $query_args = array();

    public function setPostType( PostTypeInterface $post_type ) {
        $this->post_type = $post_type;

        return $this;
    }

    public function setQueryArgs( array $query_args ) {
        $this->query_args = $query_args;

        return $this;
    }

    /**
     * Date format type
     *
     * @param string $date_format
     *
     * @return array
     */
    public function getDate( $date_format ) {
        $post_id = $this->post_id;
        $return  = array();
        switch ( $date_format ) {
            case 'full':
                $return[ 'date' ]    = \get_the_date( '', $post_id );
                $return[ 'weekday' ] = \get_the_date( 'l', $post_id );
                $return[ 'hour' ]    = \get_the_time( '', $post_id );
                break;
            case 'date':
                $return[ 'date' ]    = \get_the_date( '', $post_id );
                $return[ 'weekday' ] = \get_the_date( 'l', $post_id );
                break;
            default:
                $return[ 'date' ]    = \get_the_date( '', $post_id );
                $return[ 'weekday' ] = \get_the_date( 'l', $post_id );
                $return[ 'hour' ]    = \get_the_time( '', $post_id );
                break;
        }

        return $return;
    }

    public function getQueryArgs() {
        $query_args = array(
            'post_type'        => $this->post_type->getName(),
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'suppress_filters' => false //wpml
        );

        if ( ! is_null( $this->limit ) && $this->limit > 0 ) {
            $query_args[ 'posts_per_page' ] = $this->limit;
        } else {
            $query_args[ 'posts_per_page' ] = - 1;
        }

        if ( $this->pagination ) {
            $paged                 = \get_query_var( 'paged' ) ? \get_query_var( 'paged' ) : 1;
            $query_args[ 'paged' ] = $paged;
        }

        $query_args = \wp_parse_args( $this->query_args, $query_args );

        return $query_args;
    }

    public function getTitle() {
        $title = '';
        if ( $this->post_type->isTitle() ) {
            $title = \get_the_title( $this->post_id );
        }
        return $title;
    }

    public function getUrl() {
        $url = get_permalink( $this->post_id );
        return $url;
    }

    public function getSlug() {
        return $this->post->post_name;
    }

    public function getDescription() {
        $description = '';
        if ($this->post_type->isDescription()) {
            $description = apply_filters( 'the_content', $this->post->post_content );
        }
        return $description;
    }

    public function getShortDescription() {
        $short_description = get_the_excerpt();
        return $short_description;
    }

    public function getMetaBoxes() {
        $post_id = $this->post_id;
        $mata_box = array();
        //add meta box
        if ( $this->post_type->isMetaBoxRegistered() ) {
            $meta_box = $this->post_type->getMetaBox()->setPostId( $post_id )->getAll();
            //Fix Ticket #319 (remove empty keys)
            if ( ! empty( $meta_box ) ) {
                $mata_box = RecursiveArray::removeEmpty( $meta_box );
            }
        }

        return $mata_box;
    }

    public function getTaxonomy() {

        if ($this->post_type->isTaxonomyRegistered()) {

            if ( $this->post_type->getTaxonomy()->isRegistered( 'category' ) ) {
                $taxonomy[ 'category' ] = $this->post_type->getTaxonomy()
                                                ->setPostId( $this->post_id )
                                                ->getPostTerms( 'category' );
            }
            if ( $this->post_type->getTaxonomy()->isRegistered( 'tag' ) ) {
                $taxonomy[ 'tags' ] = $this->post_type->getTaxonomy()
                                            ->setPostId( $this->post_id )
                                            ->getPostTerms( 'tag' );
            }
        }
    }

    public function getThumbnail() {
        $image_html = '';
        if ( $this->post_type->isThumbnail( $this->post_id ) ) {
            $image_html     = \get_the_post_thumbnail(
                $this->post_id
                ,$this->post_type->thumbnail_size
            );// Thumbnail
            $image_attributes  = \wp_get_attachment_image_src(
                get_post_thumbnail_id( $this->post_id )
                ,'full'
            );
            $image_full_src = isset( $image_attributes[ 0 ] ) ? $image_attributes[ 0 ] : null;
        }

    }
}
