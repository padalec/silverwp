<?php
namespace SilverWp\Wpml;

/**
 * Import WPML settings from XML file
 *
 * @category   WordPress
 * @package    SilverWp
 * @subpackage Wpml
 * @author     Michal Kalkowski <michal at silversite.pl>
 * @copyright  SilverSite.pl (c) 2009 - 2015
 * @version    1.0
 * @since      0.5
 */
class ICLImportXML {

	/**
	 * Read setting from XML file and insert to DB
	 *
	 * @param string $file full path to settings XML file
	 *
	 * @return bool
	 * @access public
	 */
	public function importSettings( $file ) {
		global $wpdb;

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$xml = simplexml_load_file( $file );
			if ( $xml ) {
				// We can use the Views function to convert to an array
				$data = $this->importSimpleXml2array( $xml );

				// Fix array indexes
				$data['translation-management'] = array(
					'__custom_fields_readonly_config_prev' => $data['translation-management']['__custom_fields_readonly_config_prev']['item'],
					'custom_fields_readonly_config'        => $data['translation-management']['custom_fields_readonly_config']['item']
				);
				// Set the active langauges.
				foreach ( $data['wpv_active_languages'] as $code => $active ) {
					$wpdb->query(
						$wpdb->prepare(
							"UPDATE {$wpdb->prefix}icl_languages SET active=%d WHERE code='%s'",
							$active,
							$code
						)
					);
				}

				unset( $data['wpv_active_languages'] );

				update_option( 'icl_sitepress_settings', $data );

				return true;

			} else {
				return false;
			}
		}

		return false;
	}

	/**
	 * Convert XML string to array
	 *
	 * @param mixed $element
	 *
	 * @return array|int|string
	 * @access private
	 */
	private function importSimpleXml2array( $element ) {
		$element = is_string( $element ) ? trim( $element ) : $element;
		if ( ! empty( $element ) && is_object( $element ) ) {
			$element = (array) $element;
		}
		// SRDJAN - slider settings that have 0 values are imported as empty
		// string https://icanlocalize.basecamphq.com/projects/7393061-wp-views/todo_items/142382765/comments
		if ( ! is_array( $element ) && strval( $element ) == '0' ) {
			$element = 0;
		} else if ( empty( $element ) ) {
			$element = '';
		} else if ( is_array( $element ) ) {
			foreach ( $element as $k => $v ) {
				$v = is_string( $v ) ? trim( $v ) : $v;
				if ( ! is_array( $v ) && strval( $v ) == '0' ) {
					$element[ $k ] = 0;
				} else if ( empty( $v ) ) {
					$element[ $k ] = '';
					continue;
				}
				$add = $this->importSimpleXml2array( $v );
				if ( ! is_array( $add ) && strval( $add ) == '0' ) {
					$element[ $k ] = 0;
				} else if ( ! empty( $add ) ) {
					$element[ $k ] = $add;
				} else {
					$element[ $k ] = '';
				}
			}
		}

		if ( ! is_array( $element ) && strval( $element ) == '0' ) {
			$element = 0;
		} else if ( empty( $element ) ) {
			$element = '';
		}

		return $element;
	}
}
