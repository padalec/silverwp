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

if ( ! class_exists( '\SilverWp\Helper\Control\Select' ) ) {

	/**
	 * Select is equal to HTML's <select> tag. As an multiple choices input, it has choice items, defined by items.
	 *
	 * @category   WordPress
	 * @package    SilverWp
	 * @subpackage Helper\Control
	 * @author     Michal Kalkowski <michal at silversite.pl>
	 * @copyright  SilverSite.pl (c) 2015
	 * @version    0.5
	 */
	class Select extends MultiControlAbstract {
		/**
		 * Control type
		 *
		 * @var string
		 * @access protected
		 * @see    ControlAbstract::type
		 */
		protected $type = 'select';

		/**
		 * Start element
		 *
		 * @var int
		 * @access private
		 */
		private $start;

		/**
		 * End option element
		 *
		 * @var int
		 * @access private
		 */
		private $end;

		/**
		 * Class constructor
		 *
		 * @param string $name
		 *
		 * @throws Exception
		 * @see   ControlAbstract::__construct
		 * @since 0.5
		 */
		public function __construct( $name ) {
			parent::__construct( $name );
			$this->setShowEmpty( true );
		}

		/**
		 * Set min value form options in select will start
		 *
		 * @param int $start
		 *
		 * @return $this
		 * @access public
		 * @since  0.5
		 */
		public function setStart( $start ) {
			$this->start = (int) $start;

			return $this;
		}

		/**
		 * Set max option value wen select option will stop
		 *
		 * @param int $end
		 *
		 * @return $this
		 * @access public
		 * @since  0.5
		 */
		public function setEnd( $end ) {
			$this->end = (int) $end;

			return $this;
		}

		/**
		 * Get all control settings
		 *
		 * @see    ControlAbstract::getSettings()
		 * @return array
		 * @access public
		 * @since  0.5
		 */
		public function getSettings() {
			$this->gnerateOptions();

			return parent::getSettings();
		}

		/**
		 * Show or not empty option in select
		 *
		 * @param boolean $show_empty
		 *
		 * @return $this
		 * @access public
		 * @since  0.5
		 */
		public function setShowEmpty( $show_empty ) {
			$this->setting['show_empty'] = (boolean) $show_empty;

			return $this;
		}

		/**
		 * Auto generate options
		 *
		 * @access private
		 * @since  0.5
		 */
		private function gnerateOptions() {
			if ( isset( $this->start ) && isset( $this->end ) ) {
				for ( $i = $this->start; $i <= $this->end; $i ++ ) {
					$this->addOption( $i, $i );
				}
			}
		}
	}
}