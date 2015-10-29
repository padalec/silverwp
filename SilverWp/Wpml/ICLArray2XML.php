<?php
namespace SilverWp\Wpml;

use SilverWp\Debug;

if ( ! class_exists( 'SilverWp\Wpml\ICLArray2XML' ) ) {
	/**
	 * Convert settings array to XML and save to file
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       1.0
	 * @category      WordPress
	 * @package       SilverWp
	 * @subpackage    Wpml
	 * @copyright     2009 - 2015 (c) SilverSite.pl
	 * @since         0.5
	 * @todo add download file method
	 */
	class ICLArray2XML {

		/**
		 * @var string
		 * @access private
		 */
		private $text;

		/**
		 * @var int
		 * @access private
		 */
		private $depth = 0;

		/**
		 * Save settings to file
		 *
		 * @param string $file_path path to XML file will be saved
		 *
		 * @return bool|int
		 * @access public
		 */
		public function saveFile( $file_path ) {
			global $wpdb;
			$settings = get_option( 'icl_sitepress_settings', null );
			if ( $settings ) {
				if ( is_numeric( key( $settings['languages_order'] ) ) ) {
					$settings['languages_order'] = array_flip(
						$settings['languages_order']
					);
				}

				$settings['translation-management'] = array(
				    '__custom_fields_readonly_config_prev' => array(
						'__key' => 'item'
					),
					'custom_fields_readonly_config'        => array(
						'__key' => 'item'
					)
				);
				$settings['wpv_active_languages'] = array();
				// Add the active languages
				$table_name = $wpdb->prefix . 'icl_languages';
				if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) == $table_name ) {
					$results = $wpdb->get_results(
						"SELECT code, active FROM {$wpdb->prefix}icl_languages WHERE 1",
						ARRAY_A
					);
					foreach ( $results as $item ) {
						$settings['wpv_active_languages'] = array(
							$item['code'] => $item['active']
						);
					}
				}
				$data  = $this->array2xml( $settings, 'wpml' );
				$file = file_put_contents( $file_path, $data );

				return $file;
			}

			return false;
		}

		/**
		 * Generate XML
		 *
		 * @param array $array
		 * @param string $root root XML element name
		 *
		 * @return string
		 * @access private
		 */
		private function array2xml( array $array, $root ) {
			$this->depth = 1;
			$this->text  = "<?xml version=\"1.0\" encoding=\""
			               . get_option( 'blog_charset' )
			               . "\"?>\r\n<$root>\r\n";
			$this->text .= $this->arrayTransform( $array );
			$this->text .= "</$root>";

			return $this->text;
		}

		/**
		 * Transform array to XML
		 *
		 * @param array $array
		 *
		 * @return string
		 * @access private
		 */
		private function arrayTransform( array $array ) {
			$output    = '';
			$indent    = str_repeat( ' ', $this->depth * 4 );
			$child_key = false;
			if ( isset( $array['__key'] ) ) {
				$child_key = $array['__key'];
				unset( $array['__key'] );
			}
			foreach ( $array as $key => $value ) {
				if ( ! is_array( $value ) ) {
					if ( empty( $key ) ) {
						continue;
					}
					$key = $child_key ? $child_key : $key;
					$output .= $indent . "<$key>" . htmlspecialchars( $value,
							ENT_QUOTES ) . "</$key>\r\n";
				} else {
					$this->depth ++;
					$key         = $child_key ? $child_key : $key;
					$output_temp = $this->arrayTransform( $value );
					if ( ! empty( $output_temp ) ) {
						$output .= $indent . "<$key>\r\n";
						$output .= $output_temp;
						$output .= $indent . "</$key>\r\n";
					}
					$this->depth --;
				}
			}

			return $output;
		}
	}
}