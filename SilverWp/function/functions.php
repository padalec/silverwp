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
use SilverWp\Pager\Pager;

if ( ! function_exists( '\SilverWp\pager' ) ) {
	/**
	 * Generate url for pagination
	 *
	 * @param int $total_pages
	 * @param int $current_page
	 *
	 * @return array
	 * @since 0.1
	 * @author Michal Kalkowski <michal at silversite.pl>
	 */
	function pager( $total_pages, $current_page ) {
		$pager = new Pager( $total_pages, $current_page );
		$pager->setPrevArrow( '<i class="icon-left-dir"></i>' );
		$pager->setNextArrow( '<i class="icon-right-dir"></i>' );
		$pager->setDotsClass( 'page-dots' );
		$pager->setTagBeforeHref( '<li>' );
		$pager->setTagAfterHref( '</li>' );
		$pager->setNextHrefClass( 'next page-arrow' );
		$pager->setPrevHrefClass( 'prev page-arrow' );
		$pager->show_all = false;
		$links = $pager->getLinks();

		return $links;
	}
}

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
