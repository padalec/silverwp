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
namespace SilverWp\Customizer\Control;

if ( ! class_exists( 'SilverWp\Customizer\Control\Fonts' ) ) {

	/**
	 *
	 * Google and system fonts list
	 *
	 * @category   WordPress
	 * @package    SilverWp
	 * @subpackage Customizer\Control
	 * @author     Michal Kalkowski <michal at silversite.pl>
	 * @copyright  SilverSite.pl (c) 2015
	 * @version    0.1
	 */
	class Fonts extends Select {
		public function __construct( $control_name ) {
			parent::__construct( $control_name );
			//set up drop-down options for fonts
			$system_fonts = array();
			if ( function_exists( 'silverwp_get_system_fonts' ) ) {
				$system_fonts = array_flip( silverwp_get_system_fonts() );
			}
			$gwf = \Kirki_Fonts::get_font_choices();
			$fonts = array_merge( $system_fonts, $gwf );
			$this->setOptions( $fonts );
		}
	}
}