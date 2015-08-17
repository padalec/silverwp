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
namespace SilverWp\ShortCode\Vc\Control;

if ( ! class_exists( '\SilverWp\ShortCode\Vc\Control\WpEditor' ) ) {

	/**
	 * Text area with default WordPress WYSIWYG Editor.
	 * Important: only one html textarea is permitted per
	 * ShortCode and it should have "content" as a param_name
	 *
	 * @category   SilverWp
	 * @package    ShortCode
	 * @subpackage Vc\Control
	 * @author     Michal Kalkowski <michal at silversite.pl>
	 * @copyright  Silversite.pl (c) 2015
	 * @version    $Revision:$
	 */
	class WpEditor extends ControlAbstract {
		protected $type = 'textarea_html';
		public function __construct( $name ) {
			parent::__construct( 'content' );
		}
	}
}