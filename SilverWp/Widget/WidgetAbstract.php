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

namespace SilverWp\Widget;

use SilverWp\Debug;
use SilverWp\FileSystem;
use SilverWp\Helper\Control\ControlInterface;
use SilverWp\View;
use SilverWp\Widget\WidgetInterface;

if ( ! class_exists( '\SilverWp\Widget\WidgetAbstract' ) ) {
	/**
	 *
	 * Base Widget class
	 *
	 * @category   WordPress
	 * @package    SilverWp
	 * @subpackage Widget
	 * @author     Michal Kalkowski <michal at silversite.pl>
	 * @copyright  SilverSite.pl (c) 2015
	 * @version    0.4
	 * @abstract
	 */
	abstract class WidgetAbstract extends \WP_Widget
		implements WidgetInterface {

		/**
		 * Form controls
		 * @var array
		 * @access protected
		 */
		protected $controls = array();

		/**
		 * If true display all widget params
		 * @var bool
		 * @access protected
		 */
		protected $debug = false;

		/**
		 * Default widget title label
		 * @var string
		 * @access private
		 */
		private $default_title = '';

		/**
		 * Class constructor
		 *
		 * @see WP_Widget::__construct()
		 *
		 * @param null   $id_base
		 * @param string $name
		 * @param array  $widget_options
		 * @param array  $control_options
		 *
		 * @access public
		 */
		public function __construct( $id_base = null, $name, array $widget_options = array(), array $control_options = array() ) {
			parent::__construct( $id_base, $name, $widget_options, $control_options);
			add_action( 'widget_init', array( $this, 'registerFields' ) );
		}

		/**
		 * Set default title label
		 *
		 * @param string $label i18c translation string
		 *
		 * @return $this
		 * @access public
		 */
		public function setDefaultTitleLabel( $label ) {
			$this->default_title = $label;

			return $this;
		}
		/**
		 * Add form field control
		 *
		 * @param ControlInterface $control
		 *
		 * @return $this
		 * @access public
		 * @since 0.4
		 */
		public function addControl( ControlInterface $control ) {
			$this->controls[] = $control;

			return $this;
		}

		/**
		 * Register VP fields
		 *
		 * @access public
		 */
		public function registerFields() {
			$loader      = \VP_WP_Loader::instance();
			$field_types = $this->getControlsType();
			$loader->add_types( $field_types, 'widgets' );
		}

		/**
		 * Output the settings update form.
		 *
		 * @see WP_Widget::form
		 * @param array $instance Current settings.
		 *
		 * @access public
		 * @return string
		 */
		public function form( $instance ) {
			if ( $this->debug ) {
				Debug::dumpPrint( $this->controls );
			}
			Debug::dumpPrint( $this->controls );
			foreach ( $this->controls as $control ) {
				$control->addHtmlAttribute( 'id', $this->get_field_id( $control->getName() ) );
				$control->addHtmlAttribute( 'class', 'widefat' );
				$attributes = $control->getSettings();
				$attributes[ 'name' ] = $this->get_field_name( $control->getName() );

				// create field object
				$make = \VP_Util_Reflection::field_class_from_type( $attributes['type'] );
				$field        = call_user_func( "$make::withArray", $attributes );
				$default      = $field->get_default();
				//@todo rebuild this!!!
				if ( $attributes['type'] == 'checkbox'
				     && ( isset( $instance[ $control->getName() ] )
				          || is_null( $instance[ $control->getName() ] ) )
				) {
					$value = is_null( $instance[ $control->getName() ] ) ? '' : $instance[ $control->getName() ];
					$field->set_value( $value );
				} else if( isset( $instance[ $control->getName() ] ) && ! empty( $instance[ $control->getName() ] ) ) {
					$field->set_value( $instance[ $control->getName() ] );
				} else if ( ! is_null( $default ) ) {
					$field->set_value( $default );
				}
				?>
				<div>
					<label for="<?php echo $this->get_field_id( $field->get_name() ); ?>">
						<?php echo $field->get_label(); ?>
						<?php
						echo $field->render( true );
						?>
						<?php \VP_Util_Text::print_if_exists( $field->get_description(), '<div class="description">%s</div>' );?>
					</label>
				</div>
				<?php
			}
		}

		/**
		 * Get all registered controls type
		 *
		 * @return array
		 * @access private
		 */
		private function getControlsType() {
			$types = array();

			if ( ! function_exists( 'inner_build' ) ) {
				function inner_build( $controls, &$types ) {
					$rules = \VP_Util_Config::instance()
					                        ->load( 'dependencies', 'rules' );
					foreach ( $controls as $control ) {
						$field = $control->getSettings();
						if ( $field['type'] == 'group' ) {
							inner_build( $field['fields'], $types );
						} else {
							if ( ! in_array( $field['type'], $types ) ) {
								$types[] = $field['type'];
							}
						}
					}
				}
			}
			inner_build( $this->controls, $types );

			return $types;
		}

		/**
		 * Update a particular instance.
		 *
		 * This function should check that $new_instance is set correctly. The newly-calculated
		 * value of `$instance` should be returned. If false is returned, the instance won't be
		 * saved/updated.
		 *
		 * @since 0.4
		 * @access public
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            {@see WP_Widget::form()}.
		 * @param array $old_instance Old settings for this instance.
		 *
		 * @return array Settings to save or bool false to cancel saving.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			foreach ( $this->controls as $control ) {
				$instance[ $control->getName() ] = $new_instance[ $control->getName() ];
			}

			return $instance;
		}

		/**
		 * Display widget
		 *
		 * @param array $args
		 * @param array $instance
		 * @access public
		 */
		public function widget( $args, $instance ) {
			$title = empty( $instance['title'] ) ? $this->default_title : $instance['title'];
			apply_filters( 'widget_title', $title, $instance, $this->id_base );

			$this->render(
				array(
					'args'     => $args,
					'instance' => $instance,
					'this'     => $this
				)
			);
		}

		/**
		 * Render widget view
		 *
		 * @param array $data data passed too view
		 *
		 * @param null|string $view_file
		 *
		 * @return string
		 * @access protected
		 */
		protected function render( array $data, $view_file = null ) {
			if ( \is_null( $view_file ) ) {
				$view_file = $this->id_base;
			}
			try {
				$view_path = FileSystem::getDirectory( 'widgets_views' );
				$view      = View::getInstance()->load( $view_path . $view_file, $data );
				echo $view;
			} catch ( Exception $ex ) {
				echo $ex->displayAdminNotice( $ex->getMessage() );
			}
		}

	}
}