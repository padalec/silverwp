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
namespace SilverWp\Wpml;

use SilverWp\Debug;
use SilverWp\File\File;
use SilverWp\FileSystem;
use SilverWp\Helper\Message;
use SilverWp\SingletonAbstract;
use SilverWp\Translate;
use SilverWp\View;

if (!class_exists('SilverWp\Wpml\Wpml')) {
	/**
	 * WPML import/export and helper methods
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       0.5
	 * @category      WordPress
	 * @package       SilverWp
	 * @subpackage    Wpml
	 * @copyright     2009 - 2015 (c) SilverSite.pl
	 */
	class Wpml extends SingletonAbstract {

		/**
		 * @var string
		 */
		private $settings_file_path;

		/**
		 * Class constructor, register menu pages
		 *
		 * @access public
		 */
		public function __construct() {
			add_filter( 'upload_mimes', array( $this, 'mimeType'));
			add_action( 'admin_menu', array( $this, 'registerMenuPage' ) );
			global $pagenow;
			if ( $pagenow == 'tools.php'
			     && isset( $_GET['page'] )
			     && $_GET['page'] == 'wpml_export'
			) {
				add_action( 'admin_init', array( $this, 'download' ) );
			}
			//setup path to XML file
			$upload_dir = wp_upload_dir();
			$this->settings_file_path = $upload_dir['basedir'] . '/wpml_settings.xml';
		}

		/**
		 * Add XML mime type to WP mimes types
		 *
		 * @param array $mimes
		 *
		 * @return array
		 * @access public
		 */
		public function mimeType( array $mimes ) {
			$mimes = array_merge( $mimes, array( 'xml' => 'application/xml' ) );

			return $mimes;
		}

		/**
		 * Register menu page
		 *
		 * @access public
		 */
		public function registerMenuPage() {
			add_submenu_page(
				'tools.php',
				Translate::translate( 'WPML settings export' ),
				Translate::translate( 'WPML settings export' ),
				'edit_posts',
				'wpml_export',
				array( $this, 'export' )
			);
			add_submenu_page(
				'tools.php',
				Translate::translate( 'WPML settings import' ),
				Translate::translate( 'WPML settings import' ),
				'edit_posts',
				'wpml_import',
				array( $this, 'import' )
			);
		}

		/**
		 * Export WPML settings to XML file
		 *
		 * @access public
		 */
		public function export() {
			$export = new ICLArray2XML();
			?>
			<h1><?php echo Translate::translate( 'WPML settings export' ) ?></h1>
			<?php
			if ( $export->saveFile( $this->settings_file_path ) ) {
				echo Message::display(
					Translate::translate(
						'Your WPML settings have been successfully exported.
						You will find them in the wpml.xml file located in your WordPress upload folder.'
					),
					'update'
				);

			} else {
				echo Message::display(
					Translate::translate(
						'Something goes wrong. Please try again latter.'
					),
					'error'
				);
			}

		}

		/**
		 * Import WPML settings
		 *
		 * @access public
		 */
		public function import() {
			?>
			<h1><?php echo Translate::translate( 'WPML settings import' ) ?></h1>
			<?php
			if (
				isset( $_POST['wpml_settings_import'] )
				|| wp_verify_nonce( $_POST['wpml_settings_import'], 'wpml_settings_import' )
			) {
				if ( isset( $_FILES['wpml-settings'] )
				     && $_FILES['wpml-settings']['error'] == 0
				) {
					$file = wp_handle_upload( $_FILES['wpml-settings'], array( 'test_form' => false) );
					if ( isset( $file['url'] ) ) {
						$import = new ICLImportXML();
						$result = $import->importSettings( $file['url'] );
						if ( $result == true && ! is_wp_error( $result ) ) {
							echo Message::display(
								Translate::translate(
									'Your WPML settings have been successfully imported!'
								)
							);
						} else {
							echo Message::display(
								Translate::translate(
									'Something went wrong. Either WPML is not installed or the wpml.xml could not be found or be opened!'
								)
							);
							if ( is_wp_error( $result ) ) {
								echo Message::display(
									$result->get_error_message()
								);
							}
						}
						unlink( $file['file'] );
					}
				}
			}
			$this->importForm();
		}

		/**
		 * Download settings file
		 *
		 * @access public
		 */
		public function download() {
			File::download( $this->settings_file_path );
		}

		/**
		 * Import form
		 *
		 * @access private
		 */
		private function importForm() {
			?>
			<form action="<?php echo WP_SITEURL . '/wp-admin'; ?>/tools.php?page=wpml_import" method="post" enctype="multipart/form-data">
				<?php wp_nonce_field( 'wpml_settings_import', 'wpml_settings_import' ); ?>
				<div><input type="file" name="wpml-settings" id="wpml-settings"></div>
				<div>
					<input type="submit" name="send" value="<?php echo Translate::translate( 'Import') ?>">
				</div>
			</form>
			<?php
		}
		/**
		 * WPML lang switcher
		 *
		 * @static
		 * @access public
		 *
		 * @param string $view_file
		 *
		 * @return string
		 * @throws \SilverWp\Exception
		 */
		public static function langSwitcher( $view_file = 'lang-symbol-switcher' ) {
			if ( function_exists( 'icl_get_languages' ) ) {
				$args = 'skip_missing=1&orderby=code&order=ASC&link_empty_to=str';
				$languages = icl_get_languages( $args );
				$view_path = FileSystem::getDirectory( 'views' );
				$view      = View::getInstance();
				$content   = $view->load(
					$view_path . $view_file,
					array( 'data' => $languages )
				);

				return $content;
			}

			return false;
		}

		/**
		 * Returns the translated object ID(post_type or term) or original if missing
		 *
		 * @param $object_id integer|string|array The ID/s of the objects to check and return
		 * @param $type      the object type: post, page, {custom post type name}, nav_menu, nav_menu_item, category, tag etc.
		 *
		 * @return string or array of object ids
		 */
		public static function translate_object_id( $object_id, $type ) {
			$icl_get_current_language = ICL_LANGUAGE_CODE;
			// if array
			if ( is_array( $object_id ) ) {
				$translated_object_ids = array();
				foreach ( $object_id as $id ) {
					$translated_object_ids[] = apply_filters( 'wpml_object_id',
						$id, $type, true, $icl_get_current_language );
				}

				return $translated_object_ids;
			}
			// if string
			elseif ( is_string( $object_id ) ) {
				// check if we have a comma separated ID string
				$is_comma_separated = strpos( $object_id, "," );

				if ( $is_comma_separated !== false ) {
					// explode the comma to create an array of IDs
					$object_id = explode( ',', $object_id );

					$translated_object_ids = array();
					foreach ( $object_id as $id ) {
						$translated_object_ids[]
							= apply_filters( 'wpml_object_id', $id, $type, true,
							$icl_get_current_language );
					}

					// make sure the output is a comma separated string (the same way it came in!)
					return implode( ',', $translated_object_ids );
				} // if we don't find a comma in the string then this is a single ID
				else {
					return apply_filters( 'wpml_object_id',
						intval( $object_id ), $type, true,
						$icl_get_current_language );
				}
			} // if int
			else {
				return apply_filters( 'wpml_object_id', $object_id, $type, true,
					$icl_get_current_language );
			}
		}
	}
}