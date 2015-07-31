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
namespace SilverWp\Helper\Control;

if ( ! class_exists( '\SilverWp\Helper\Control\Notebox' ) ) {

	/**
	 *
	 * Notebox isn't really a control, it's only serve as a
	 * way to give a notice to your users in a note box fashion.
	 *
	 * @category   WordPress
	 * @package    SilverWp
	 * @subpackage Helper\Control
	 * @author     Michal Kalkowski <michal at silversite.pl>
	 * @copyright  Dynamite-Studio.pl & silversite.pl 2015
	 * @version    $Revision:$
	 */
	class Notebox extends ControlAbstract {
		protected $type = 'notebox';

		/**
		 * Class constructor
		 *
		 * @param string $name default empty string
		 *
		 * @throws Exception
		 */
		public function __construct( $name = '' ) {
			parent::__construct( $name );
        }

		/**
		 * Status of the note, supported status are:
		 * normal, info, warning, error, success, default to normal.
		 *
		 * @param string $status
		 *
		 * @access public
		 */
		public function setStatus( $status ) {
			$this->setting['status'] = $status;

			return $this;
		}
	}
}