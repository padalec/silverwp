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
namespace SilverWp\Sidebar\Widget;

use SilverWp\Translate;
use SilverWp\View;
use WPH_Widget;

/**
 * Widget Abstract class
 *
 * @author        Michal Kalkowski <michal at silversite.pl>
 * @version       $Id: WidgetAbstract.php 2184 2015-01-21 12:20:08Z padalec $
 * @category      WordPress
 * @package       Sidebar
 * @subpackage    Widget
 * @link          https://github.com/sksmatt/WordPress-Widgets-Helper-Class/blob/master/wph-widget-class.php
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
abstract class WidgetAbstract extends WPH_Widget {
	public function createWidget( $args ) {
		parent::create_widget( $args );
		add_action( 'save_post', array( &$this, 'flushWidgetCache' ) );
		add_action( 'deleted_post', array( &$this, 'flushWidgetCache' ) );
		add_action( 'switch_theme', array( &$this, 'flushWidgetCache' ) );
	}

	public function beforeUpdateFields() {
		$this->flushWidgetCache();
		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions[ $this->slug ] ) ) {
			delete_option( $this->slug );
		}
	}

	public function flushWidgetCache() {
		wp_cache_delete( $this->slug, 'widget' );
	}

	/**
	 * Update Fields
	 *
	 * @access   private
	 *
	 * @param    array
	 * @param    array
	 *
	 * @return   array
	 * @since    1.0
	 * @todo     fix bug if validate is set don't change new insttance value
	 * @todo     fix filter when new_istance data is array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$this->beforeUpdateFields();

		foreach ( $this->fields as $key ) {
			$slug = $key['id'];
			#bug if validate is set don't change new insttance value
			/*if ( isset( $key['validate'] ) ) {
				if ( false === $this->validate( $key['validate'], $new_instance[$slug] ) )
				return $instance;
			}*/
			//#bug fix filter when new_istance data is array
			if ( isset( $key['filter'] ) ) {
				$instance[ $slug ] = $this->filter( $key['filter'],
					$new_instance[ $slug ] );
			} else {
				//fix bug when value is array add filter to all elements
				$instance[ $slug ] = is_array( $new_instance[ $slug ] )
					? array_map( 'strip_tags', $new_instance[ $slug ] )
					: strip_tags( $new_instance[ $slug ] );
			}

		}

		return $this->after_validate_fields( $instance );
	}

	/**
	 *
	 * checkbox tree with post categories
	 *
	 * @param array $key array with selected categories
	 *
	 * @return string
	 */
	public function create_field_category_cb( $key ) {
		$output = '';
		//$output = '<p>';
		//$output .= '<label for="'.$this->get_field_id('cats').'"><br />';
		//$output .= Translate::e('Select categories to include in the recent posts list:');
		//$output .= '</p>';
		$output .= $this->create_field_label( $key['name'], $key['_id'] )
		           . '<br/>';
		$categories = get_categories( 'hide_empty=0' );

		$option = '';
		foreach ( $categories as $cat ) {
			$option .= '<label><input type="checkbox" id="'
			           . esc_attr( $key['_id'] ) . '" ';
			$option .= 'name="' . esc_attr( $key['_name'] ) . '[]"';
			if ( isset( $key['value'] ) && is_array( $key['value'] )
			     && count( $key['value'] )
			) {
				foreach ( $key['value'] as $cats ) {
					if ( $cats == $cat->term_id ) {
						$option = $option . ' checked="checked"';
					}
				}
			}
			$option .= ' value="' . $cat->term_id . '" /> ';
			$option .= $cat->cat_name;
			$option .= '</label><br />';
		}
		$output .= $option; //. '</label>';
		return $output;
	}

	/**
	 *
	 * checkbox tree with post categories
	 *
	 * @param array $key array with selected categories
	 *
	 * @return string
	 */
	public function create_field_category_portfolio_cb( $key ) {
		$output = '';
		//$output = '<p>';
		//$output .= '<label for="'.$this->get_field_id('cats').'"><br />';
		//$output .= Translate::e('Select categories to include in the recent posts list:');
		//$output .= '</p>';
		$output .= $this->create_field_label( $key['name'], $key['_id'] )
		           . '<br/>';
		$Portfolio  = \SilverWp\PostType\Portfolio::getInstance();
		$categories = $Portfolio->getTaxonomy()->getAllTerms();
		$option     = '';
		foreach ( $categories as $category ) {
			$option .= '<label><input type="checkbox" id="'
			           . \esc_attr( $key['_id'] ) . '" ';
			$option .= 'name="' . \esc_attr( $key['_name'] ) . '[]"';
			if ( isset( $key['value'] ) && \is_array( $key['value'] )
			     && \count( $key['value'] )
			) {
				foreach ( $key['value'] as $cats ) {
					if ( $cats == $category['term_id'] ) {
						$option = $option . ' checked="checked"';
					}
				}
			}
			$option .= ' value="' . $category['term_id'] . '" /> ';
			$option .= $category['name'];
			$option .= '</label><br />';
		}
		$output .= $option; //. '</label>';
		return $output;
	}

	/**
	 *
	 * @param type $key
	 * @param type $out
	 *
	 * @return type
	 */
	public function create_field( $key, $out = '' ) {
		/* Set Defaults */
		$key['std'] = isset( $key['std'] ) ? $key['std'] : "";

		$slug = $key['id'];
		//remove strip tags from insttance becouse this doesn't work when is array
		if ( isset( $this->instance[ $slug ] ) ) {
			$key['value'] = empty( $this->instance[ $slug ] ) ? ''
				: $this->instance[ $slug ];
		} else {
			unset( $key['value'] );
		}
		/* Set field id and name  */
		$key['_id']   = $this->get_field_id( $slug );
		$key['_name'] = $this->get_field_name( $slug );

		/* Set field type */
		if ( ! isset( $key['type'] ) ) {
			$key['type'] = 'text';
		}

		/* Prefix method */
		$field_method = 'create_field_' . \str_replace( '-', '_',
				$key['type'] );

		/* Check for <p> Class */
		$p = ( isset( $key['class-p'] ) ) ? '<p class="' . $key['class-p']
		                                    . '">' : '<p>';

		/* Run method */
		if ( \method_exists( $this, $field_method ) ) {
			return $p . $this->$field_method( $key ) . '</p>';
		} else {
			throw new Exception( Translate::translate( 'Field ' . $key['type']
			                                           . ' doesn\'t exists.' ) );
		}
	}

	/**
	 * render view
	 *
	 * @param array $data data passed too view
	 *
	 * @return string
	 * @access protected
	 */
	protected function render( array $data, $view_file = null ) {
		if ( \is_null( $view_file ) ) {
			$view_file = $this->id_base;
		}
		try {
			$view = View::getInstance()->load( 'Widget/' . $view_file, $data );
			echo $view;
		} catch ( Exception $ex ) {
			echo $ex->displayAdminNotice( $ex->getMessage() );
		}
	}
}
