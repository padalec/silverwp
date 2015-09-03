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
use SilverWp\Translate;
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

		protected $controls = array();
		protected $debug = false;

		public function __construct( $id_base = null, $name, array $widget_options = array(), array $control_options = array() ) {
			parent::__construct( $id_base, $name, $widget_options, $control_options);
			add_action( 'init', array( $this, 'register_fields' ) );
		}

		public function addControl(ControlInterface $control) {
			$this->controls[] = $control;

			return $this;
		}

		public function register_fields() {
			$loader      = \VP_WP_Loader::instance();
			$field_types = $this->get_field_types();
			if ( $this->debug ) {
				Debug::dumpPrint( $field_types );
			}
			$loader->add_types( $field_types, 'widgets' );
		}

		public function form( $instance ) {
			Debug::dumpPrint($instance);
			foreach ( $this->controls as $control ) {
				$attr = $control->getSettings();
				$field_name = $attr['name'];
				// create the object
				$make = \VP_Util_Reflection::field_class_from_type( $attr['type'] );
				$field        = call_user_func( "$make::withArray", $attr );
				$default      = $field->get_default();
				if ( ! is_null( $default ) ) {
					$field->set_value( $default );
				} else {
					$field->set_value( $instance[ $field_name ] );
				}
				?>

				<?php if ( $attr['type'] !== 'notebox' ): ?>
					<div class="vp-sc-field vp-<?php echo $attr['type']; ?>"
					     data-vp-type="vp-<?php echo $attr['type']; ?>">
						<div class="label">
							<label><?php echo $attr['label']; ?></label>
						</div>
						<div class="field">
							<div class="input"><?php echo $field->render( true ); ?></div>
						</div>
					</div>
				<?php else: ?>
					<?php $status = isset( $attr['status'] )
						? $attr['status'] : 'normal'; ?>
					<div
						class="vp-sc-field vp-<?php echo $attr['type']; ?> note-<?php echo $status; ?>"
						data-vp-type="vp-<?php echo $attr['type']; ?>">
						<?php echo $field->render( true ); ?>
					</div>
				<?php endif; ?>

				<?php
			}

		}

		private function get_field_types() {
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

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance[ 'number' ] = 10;
			Debug::dumpPrint($new_instance);
			foreach ( $this->controls as $control ) {
				if (! empty( $new_instance[ $control->getName() ] )) {
					$instance[ $control->getName() ] = $new_instance[ $control->getName() ];
				} else {
					$instance[ $control->getName() ] = $old_instance[$control->getName() ];
				}
			}
			Debug::dumpPrint($instance);
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
			$title = empty( $instance['title'] ) ? Translate::translate( 'Recent Posts with Image' ) : $instance['title'];
			apply_filters( 'widget_title', $title, $instance, $this->id_base );

			$this->render(
				array(
					'args'     => $args,
					'instance' => $instance,
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