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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Taxonomy/TaxonomyAbstract.php $
  Last committed: $Revision: 2283 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-29 17:27:04 +0100 (Cz, 29 sty 2015) $
  ID: $Id: TaxonomyAbstract.php 2283 2015-01-29 16:27:04Z padalec $
 */
namespace SilverWp\Taxonomy;

use SilverWp\Helper\Filter;
use SilverWp\Helper\UtlArray;
use SilverWp\PostInterface;
use SilverWp\SingletonAbstract;
use SilverWp\Taxonomy\TaxonomyInterface;
use SilverWp\Translate;

/**
 *
 * Taxonomy Abstract
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: TaxonomyAbstract.php 2283 2015-01-29 16:27:04Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage Taxonomy
 * @abstract
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
abstract class TaxonomyAbstract extends SingletonAbstract implements TaxonomyInterface, PostInterface {
    /**
     *
     * single or list post type objects names
     * where taxonomy shouldby displayed
     * if singel shouldby passed array
     *
     * @var array
     */
    protected $object_type = array();
    /**
     *
     * @var string
     */
    protected $post_type;
    /**
     * taxonomies to register
     *
     * @var array sample:
     * array(
     * 'name'   => '',
     * 'args'   => array(
     * 'public'            => true,
     * 'show_in_nav_menus' => true,
     * 'show_ui'           => true,
     * 'show_tagcloud'     => true,
     * 'hierarchical'      => true,
     * 'query_var'         => true
     * ),
     * )
     */
    protected $taxonomies = array(
        array(
            'name' => '',
            'args' => array(
                'public'            => true,
                'show_in_nav_menus' => true,
                'show_ui'           => true,
                'show_tagcloud'     => true,
                'hierarchical'      => true,
                'query_var'         => true
            ),
        )
    );
    /**
     * list of all labels shoul by setsup
     *
     * @var array
     * @access private
     * @since 1.8
     */
    protected $labels = array(
        'name' => array(
            'name'                       => '',
            'singular_name'              => '',
            'menu_name'                  => '',
            'all_items'                  => 'All items',
            'parent_item'                => 'Parent',
            'parent_item_colon'          => 'Parent',
            'update_item'                => 'Update ',
            'separate_items_with_commas' => 'Separate with commas',
            'choose_from_most_used'      => 'Choose from the most used ',
        )
    );

    /**
     *
     * variable handle all taxonomies names
     *
     * @var array
     * @access private
     */
    private $taxonomy_name = array();
    /**
     * post id default null
     *
     * @var integer
     * @access protected
     */
    protected $post_id = null;

    /**
     *
     * calss constructor
     *
     * @access protected
     * @return void
     */
    protected function __construct() {
        //set labels
        $this->setLabels();
        // Adds taxonomies
        \add_action( 'init', array( $this, 'init' ), 0 );
        // Allows filtering of posts by taxonomy in the admin view
        \add_action( 'restrict_manage_posts', array( $this, 'filterRestrictManagePosts' ) );
        \add_filter( 'parse_query', array( $this, 'addFilter2QueryList' ), 10, 1 );
    }

    /**
     * return all defailt labels
     *
     * @since 1.8
     * @return array
     * @access private
     */
    private function getDefaultLabels() {
        $defaultlabels = array(
            'search_items'        => Translate::translate( 'Search' ),
            'popular_items'       => Translate::translate( 'Popular' ),
            'add_new_item'        => Translate::translate( 'Add New' ),
            'new_item_name'       => Translate::translate( 'New name' ),
            'edit_item'           => Translate::translate( 'Edit' ),
            'add_or_remove_items' => Translate::translate( 'Add or remove' ),
        );

        return $defaultlabels;
    }

    /**
     *
     * add new taxonomy
     *
     * @param array $taxonomy
     *
     * @access public
     * @return void
     */
    public function addTaxonomy( array $taxonomy ) {
        $this->taxonomies[ ] = $taxonomy;
        \array_unique( $this->taxonomies );
    }

    /**
     * add new taxonomy name
     *
     * @param string $taxonomy
     */
    private function addTaxonomyName( $taxonomy ) {
        $this->taxonomy_name[ ] = $taxonomy;
        \array_unique( $this->taxonomy_name );
    }

    /**
     *
     * set post type
     *
     * @param string $post_type
     *
     * @access public
     */
    public function setPostType( $post_type ) {
        $this->post_type = $post_type;

        return $this;
    }

    /**
     *
     * get post type name
     *
     * @return string
     * @access public
     */
    public function getPostType() {
        return $this->post_type;
    }

    /**
     * set all not default labels
     *
     * @since 1.8
     * @abstract
     * @access protected
     */
    abstract protected function setLabels();

    /**
     *
     * set object post type name where
     * taxonomy shouldby refferences
     *
     * @param array $post_type
     *
     * @access public
     */
    public function setObjectType( array $post_type ) {
        $this->object_type = \array_unique( \array_merge( $this->object_type, $post_type ) );
    }

    /**
     *
     * init taxnonomy
     *
     * @access public
     * @throws Exception
     */
    public function init() {
        try {
            if ( \is_null( $this->post_type ) ) {
                throw new Exception( Translate::translate( '$post_type is required and can\'t be empty.' ) );
            }
            foreach ( $this->taxonomies as $value ) {
                $name = $this->getSlug( $value[ 'name' ] );
                $this->addTaxonomyName( $name );
                $args = $this->getArgs( $value );
                \register_taxonomy( $name, $this->object_type, $args );
                \register_taxonomy_for_object_type( $name, $this->post_type );
            }
        } catch ( Exception $ex ) {
            $ex->displayAdminNotice();
        }
    }

    /**
     *
     * create array for register_taxonomy
     *
     * @param array $args - args
     *
     * @return array
     * @throws Exception
     * @access private
     */
    private function getArgs( $args ) {
        if ( ! isset( $args[ 'args' ] ) ) {
            throw new Exception( Translate::translate( 'Parametr args in $this->taxonomies is required!' ) );
        }
        $labels = \wp_parse_args( $this->labels[ $args[ 'name' ] ], $this->getDefaultLabels() );

        $taxonomy_args = array(
            'labels'  => $labels,
            'rewrite' => array(
                'slug'       => $this->getSlug( $args[ 'name' ] ),
                'with_front' => true
            ),
        );
        $args          = \wp_parse_args( $args[ 'args' ], $taxonomy_args );

        return $args;
    }

    /**
     * create uniqe slug for taxonomy
     *
     * @param string $taxonomy_name
     *
     * @return string
     * @access private
     */
    private function getSlug( $taxonomy_name ) {
        return $this->post_type . '_' . $taxonomy_name;
    }

    /**
     * get all registered taxonomies name
     *
     * @return array
     * @access public
     */
    public function getName( $name = null ) {
        foreach ( $this->taxonomies as $value ) {
            if ( isset( $value[ 'name' ] ) && ! \is_null( $name ) && $value[ 'name' ] == $name ) {
                return $this->getSlug( $value[ 'name' ] );
            } else {
                $tax_name[ ] = $this->getSlug( $value[ 'name' ] );
            }
        }

        return $tax_name;
    }

    /**
     * Adds taxonomy filters to the admin page in lists
     *
     * @link http://pippinsplugins.com code artfully lifed
     * @access public
     * @return void
     */
    public function filterRestrictManagePosts() {
        global $typenow;
        // An array of all the taxonomyies you want to display. Use the taxonomy name or slug
        // must set this to the post type you want the filter(s) displayed on
        if ( $typenow == $this->post_type ) {
            $taxonomies = \get_object_taxonomies( $typenow );
            foreach ( $taxonomies as $tax_slug ) {

                $tax_obj = \get_taxonomy( $tax_slug );

                if ( \wp_count_terms( $tax_slug ) ) {
                    \wp_dropdown_categories(
                        array(
                            'show_option_all' => Translate::translate( 'Show All ' . $tax_obj->label ),
                            'taxonomy'        => $tax_slug,
                            'name'            => $tax_obj->name,
                            'orderby'         => 'name',
                            'selected'        => Filter::get_var( $tax_slug ),
                            'hierarchical'    => $tax_obj->hierarchical,
                            'show_count'      => false,
                            'hide_empty'      => true
                        )
                    );
                }
            }
        }
    }

    /**
     * get terms
     *
     * @param string $term_name taxonomy name
     * @param array  $param - query args http://codex.wordpress.org/Function_Reference/get_terms
     *
     * @throws Exception
     * @link http://codex.wordpress.org/Function_Reference/get_terms get_terms function
     * @access public
     * @return array
     */
    public function getAllTerms( $term_name = 'category', array $param = array() ) {
        $default_args = array(
            'orderby'      => 'name',
            'order'        => 'ASC',
            'hide_empty'   => false,
            'exclude'      => array(),
            'exclude_tree' => array(),
            'include'      => array(),
            'number'       => '',
            'fields'       => 'all',
            'slug'         => '',
            'parent'       => '',
            'hierarchical' => true,
            'child_of'     => 0,
            'name__like'   => '',
            'pad_counts'   => false,
            'offset'       => '',
            'search'       => '',
            'cache_domain' => 'core'
        );

        $args = \wp_parse_args( $param, $default_args );

        $tax_category = $this->getName( $term_name );
        $tax_list     = UtlArray::object_to_array( \get_terms( $tax_category, $args ) );
        if ( \is_wp_error( $tax_list ) ) {
            throw new Exception( $tax_list->get_error_message() );
        }

        return $tax_list;
    }

    /**
     *
     * get terms directed to the post
     *
     * @param string $term_name category or tag
     *
     * @return array
     * @throws Exception
     * @access public
     */
    public function getPostTerms( $term_name = 'category' ) {
        $query_args = array(
            'orderby' => 'name',
            'order'   => 'ASC',
            'fields'  => 'all',
        );
        if ( \is_null( $this->post_id ) ) {
            $child_class = \get_called_class();
            throw new Exception(
                Translate::params( 'Variable %s::post_id is required and can\'t be empty', $child_class )
            );
        }
        $post_id  = $this->post_id;
        $tax_name = $this->getName( $term_name );
        $Terms    = \wp_get_object_terms( (int) $post_id, $tax_name, $query_args );

        if ( \is_wp_error( $Terms ) ) {
            throw new Exception( $Terms->get_error_message(), $Terms->get_error_code() );
        }
        $terms = UtlArray::object_to_array( $Terms );

        return $terms;
    }

    /**
     * set post_id varibale
     *
     * @param integer $post_id post id
     *
     * @return \SilverWp\Taxonomy\TaxonomyAbstract
     * @access public
     */
    public function setPostId( $post_id ) {
        $this->post_id = $post_id;

        return $this;
    }

    /**
     * get post id
     *
     * @return integer|null value of post_id variable
     * @access public
     */
    public function getPostId() {
        return $this->post_id;
    }

    /**
     * check if taxonomy $name is registered
     *
     * @param string $name taxonomy name
     *
     * @return boolean
     * @access public
     */
    public function isRegistered( $name ) {
        if ( \in_array( $this->getName( $name ), $this->taxonomy_name ) ) {
            return true;
        }

        return false;
    }

    /**
     * get query args for get post by taxonomy id
     *
     * @param array|integer $tax_ids array with taxonomy id or if only one
     *
     * @return array
     * @access public
     */
    public function getCategoryQueryArgs( $tax_ids ) {
        $args[ 'tax_query' ] = array(
            array(
                'taxonomy' => $this->getName( 'category' ),
                'field'    => 'term_id',
                'terms'    => $tax_ids,
            )
        );

        return $args;
    }

    /**
     * add teaxonomy slug to query for filter by taxonomy
     *
     * @global string $pagenow current page
     *
     * @param object  $query Wp_query instance
     *
     * @access public
     * @return void
     */
    public function addFilter2QueryList( $query ) {
        global $pagenow;
        $post_type  = $this->getPostType();
        $taxonomy   = $this->getName( 'category' );
        $query_vars = &$query->query_vars;
        if ( $pagenow == 'edit.php' &&
             isset( $query_vars[ 'post_type' ] ) &&
             $query_vars[ 'post_type' ] == $post_type &&
             isset( $query_vars[ $taxonomy ] ) &&
             is_numeric( $query_vars[ $taxonomy ] ) &&
             $query_vars[ $taxonomy ] != 0
        ) {
            $term                    = get_term_by( 'id', $query_vars[ $taxonomy ], $taxonomy );
            $query_vars[ $taxonomy ] = $term->slug;
        }
    }
}
