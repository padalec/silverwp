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
namespace SilverWp;

use SilverWp\Customizer\CustomizerAbstract;
use SilverWp\Helper\Option;
use SilverWp\Helper\Thumbnail;
use SilverWp\Helper\Control\SidebarPosition;
use Roots\Sage\Config;

if ( ! function_exists( '\SilverWp\get_customizer_option' ) ) {
	/**
	 * Short cut to CustomizerAbstract::getOption()
	 *
	 * @param string $option_name
	 *
	 * @return string
	 * @access public
	 * @since 0.2
	 * @author Michal Kalkowski <michal at silversite.pl>
	 */
	function get_customizer_option( $option_name ) {
		return CustomizerAbstract::getOption( $option_name );
	}
}

if ( ! function_exists( '\SilverWp\get_theme_option' ) ) {
    /**
     * Short cut to SilverWp\Helper\Option::get_theme_option()
     *
     * @param string $option_name
     *
     * @return string
     * @access public
     * @author Marcin Dobroszek <marcin at silversite.pl>
     * @since 0.2
     */
    function get_theme_option( $option_name ) {
        return Option::get_theme_option( $option_name );
    }
}

if ( ! function_exists( '\SilverWp\get_template_part' ) ) {

	/**
	 * Load template part with parameters
	 *
	 * @param string $template_name template name
	 * @param array $params - associative array with
	 *                      variable_name => variable_value
	 *                      then in template will be available $variable_name
	 *
	 * @return string
	 * @access public
	 * @since 0.2
	 * @author Michal Kalkowski <michal at silversite.pl>
	 */
	function get_template_part( $template_name, array $params = array() ) {
		extract( $params );

		return include( locate_template( "$template_name.php" ) );
	}
}

if ( ! function_exists( '\SilverWp\get_attachment_image_from_url' ) ) {

    /**
     * Returns an HTML image element representing an attachment file.
     *
     * @param string $image_file_url - URL of media
     * @param string $size - size name of image
     *
     * @return string
     * @access public
     * @author Marcin Dobroszek <marcin at silversite.pl>
     */
    function get_attachment_image_from_url( $image_file_url, $size = 'thumbnail' ) {
        $attachmentId = Thumbnail::getAttachmentIdFromUrl( $image_file_url );
        return wp_get_attachment_image( $attachmentId, $size );
    }
}

if ( ! function_exists( '\SilverWp\get_image_size' ) ) {

    /**
     * Returns name of image size.
     *
     * @param int $col_num - number of grid columns
     * @param string $format - normal size (thumbnail) or wide image (featured or single view) - "thumbnail" / "featured"
     *
     * @return string
     * @access public
     * @author Marcin Dobroszek <marcin at silversite.pl>
     */
    function get_image_size( $col_num, $format = 'thumbnail' ) {
        $hasSidebar = Config\display_sidebar() && SidebarPosition::isDisplayed(); // aside

        if ( $hasSidebar ) { // content with Sidebar
            if ( $format === 'thumbnail' ) { // single column width
                switch ($col_num) {
                    case 1: return 'grid-66'; // 2/3 width of container
                    case 2: return 'grid-33'; // 1/3 width of container
                    case 3: return 'thumbnail';
                }
            }
            else { // featured box or single view - double or triple column width
                return 'grid-66'; // 2/3 width of container  /  full width of Main content
            }
        }
        else { // content without sidebar
            if ( $format === 'thumbnail' ) { // single column width
                switch ($col_num) {
                    case 1: return 'full-container'; // full width of container
                    case 2: return 'half-container'; // 1/2 width of container
                    case 3: return 'grid-33'; // 1/3 width of container
                }
            }
            else { // featured box or single view - double or triple column width
                return 'full-container'; // full width of container  /  full width of Main content
            }
        }
    }
}