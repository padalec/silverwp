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

use SilverWp\Customizer\Section\SectionAbstract;
use SilverWp\Debug;
use SilverWp\FileSystem;
use SilverWp\Helper\MetaBox;

if ( ! class_exists( '\SilverWp\Customizer\Control\Fontello' ) ) {

	/**
	 * Combo box with fontello icons (select2) control
	 *
	 * @category   WordPress
	 * @package    SilverWp
	 * @subpackage Customizer\Control
	 * @author     Michal Kalkowski <michal at silversite.pl>
	 * @copyright  SilverSite.pl 2015
	 * @version    $Revision:$
	 * @see        https://github.com/aristath/kirki/wiki/select2
	 */
	class Fontello extends Select {
		/**
		 * Class constructor
		 *
		 * @param string $name
		 *
		 * @access public
		 */
		public function __construct( $name ) {
			parent::__construct( $name );
			$css_path = FileSystem::getDirectory( 'fonts_path' ) . 'fontello.css';
			$items   = SectionAbstract::flipSourceData(
				MetaBox::getFontelloIcons( 'icon', $css_path, 'silverwp_fontello_icons' )
			);

			$this->setOptions( $items );
		}
	}
}