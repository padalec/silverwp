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

namespace SilverWp\ManagePosts;


use SilverWp\PostType\PostTypeInterface;

if ( ! class_exists( '\SilverWp\ManagePosts\CustomColumn' ) ) {

    /**
     *
     * 
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage 
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright SilverSite.pl (c) 2015
     * @version 0.1
     * @since 0.5
     */
        
    class CustomColumns implements CustomColumnsInterface {

	    protected $posts_types = array();

	    protected $columns = array();

	    /**
	     * List of columns that should be
	     * exclude from edit table
	     *
	     * @var array
	     * @access protected
	     */
	    protected $exclude_columns = array();

	    /**
	     * Manage custom columns in edit screen
	     *
	     * @access private
	     */
	    public function manageColumns() {
		    if ( is_admin() ) {
			    // Adds columns in the admin view for thumbnail and taxonomies
			    foreach ( $this->posts_types as $name ) {
				    add_filter( 'manage_' . $name . '_posts_columns', array( $this, 'setColumnsLabels' ), 10, 1 );
				    add_action( 'manage_' . $name . '_posts_custom_column', array( $this, 'customColumns' ), 10, 2 );
			    }
		    }
	    }

	    /**
	     * Set post type will custom columns will be changed
	     *
	     * @param array|PostTypeInterface[] $post_type
	     *
	     * @return $this
	     * @access public
	     */
		public function setPostTypes( $post_type ) {

			if ( $post_type instanceof PostTypeInterface ) {
				$this->posts_types = array( $post_type->getName() );
			} elseif ( is_array( $post_type ) ) {
				$this->posts_types = $post_type;
			}

			return $this;
		}

	    /**
	     * @param array $columns
	     *
	     * @return $this
	     * @access public
	     */
	    public function setExcludeColumns( array $columns ) {
		    $this->exclude_columns = $columns;

		    return $this;
	    }

	    /**
	     * Add columns labels to edit screen
	     *
	     * @access public
	     *
	     * @param array $columns
	     *
	     * @return array
	     */
	    public function setColumnsLabels( $columns ) {
		    $unique_cols   = array( 'category', 'thumbnail', 'tag' );
		    $columns_list = $this->getColumns();
		    foreach ( $columns_list as $key => $value ) {
			    foreach ( $this->taxonomies as $name => $args ) {
				    if ( isset( $args['display_column'] ) && $args['display_column'] ) {
					    if ( \in_array( $key, $unique_cols ) ) {
						    $key = $name . '_' . $key;
					    }

					    if ( isset( $value['label'] ) ) {
						    $columns[ $key ] = $value['label'];
					    } elseif ( isset( $value['html'] ) ) {
						    $columns[ $key ] = $value['html'];
					    }
				    }
			    }
		    }

		    return $columns;
	    }

	    /**
	     *
	     * Add custom columns in edit screen
	     *
	     * @param string $column column name
	     * @param int    $post_id
	     *
	     * @access public
	     */
	    public function customColumns( $column, $post_id ) {
		    try {
//				todo move to meta box
//              if ( $column == $this->id . '_thumbnail' ) {
//					// Display the featured image in the column view if possible
//					if ( \has_post_thumbnail( $post_id ) ) {
//						\the_post_thumbnail( $this->column_image_size );
//					} else {
//						echo Translate::translate( 'None' );
//					}
//				}
			    // Display taxonomies in the column view
			    foreach ( $this->columns as $taxonomy_name => $args ) {
				    if ( isset( $args['display_column'] ) && $args['display_column'] && $column == $taxonomy_name) {
					    if ( has_term( '', $taxonomy_name, $post_id ) ) {
						    $terms_list = get_the_term_list( $post_id, $taxonomy_name, '', ', ', '' );

						    if ( is_wp_error( $terms_list ) ) {
							    throw new Exception(
								    $terms_list->get_error_message() . ': ' . $taxonomy_name
							    );
						    }
						    if ( $terms_list ) {
							    echo $terms_list;
						    } else {
							    echo Translate::translate( 'None' );
						    }
					    }
				    }
			    }

		    } catch ( Exception $ex ) {
			    echo $ex->displayAdminNotice();
		    }
	    }
	    /**
	     *
	     * get list of edit columns displayed in lists of Post Type
	     *
	     *
	     * list of columns displayed in dashboard list. Example
	     * array(
	     *       'cb' => array(
	     *           'html' => '<input type="checkbox" />',
	     *       ),
	     *       'title' => array(
	     *           'label' => 'Title',
	     *       ),
	     *       'category' => array(
	     *            'label' => 'Categories',
	     *       ),
	     *       'thumbnail' => array(
	     *           'label' => 'Thumbnail',
	     *       ),
	     *       'tag' => array(
	     *           'label' => 'Tags',
	     *      ),
	     *      'date' => array(
	     *          'label' => 'Date',
	     *      ),
	     *      'author' => array(
	     *          'label' => 'Author',
	     *      ),
	     *  );
	     *
	     * @access protected
	     * @return array
	     */
	    protected function getEditColumns() {
		    $columns_default = array(
			    'cb'                     => array(
				    'html' => '<input type="checkbox" />',
			    ),
			    'title'                  => array(
				    'label' => Translate::translate( 'Title' ),
			    ),
			    'thumbnail'              => array(
				    'label' => Translate::translate( 'Thumbnail' ),
			    ),
			    'author'                 => array(
				    'label' => Translate::translate( 'Author' ),
			    ),
			    'date'                   => array(
				    'label' => Translate::translate( 'Date' ),
			    ),
			    'category'               => array(
				    'label' => Translate::translate( 'Categories' ),
			    ),
			    'tag'                    => array(
				    'label' => Translate::translate( 'Tags' ),
			    ),
		    );

		    foreach ( $this->columns as $name => $label ) {
		        $columns_default[ $name ][ 'label' ] = $label;
			}

		    $columns = UtlArray::array_remove_part( $columns_default, $this->exclude_columns );

		    return $columns;
	    }
    }
}