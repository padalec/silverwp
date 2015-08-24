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
namespace SilverWp\Pager;

if ( ! class_exists( 'SilverWp\Pager\Pager' ) ) {
	/**
	 * Pager
	 *
	 * @author         Michal Kalkowski <michal at silversite.pl>
	 * @version        1.0
	 * @category       WordPress
	 * @package        SilverWp
	 * @subpackage     Helper
	 * @copyright (c)  2009 - 2014, SilverSite.pl
	 */
	class Pager implements PagerInterface {

		/**
		 * The current page number. Default: 0
		 *
		 * @var integer
		 * @access protected
		 */
		protected $current_page = 0;

		/**
		 * The total amount of pages. Default: 1
		 *
		 * @var integer
		 * @access protected
		 */
		protected $total_pages = 1;

		/**
		 * Arrow prev
		 *
		 * @var string
		 * @access protected
		 */
		protected $prev_arrow = '<i class="icon-left-open-big"></i>';

		/**
		 * Arrow next
		 *
		 * @var string
		 * @access protected
		 */
		protected $next_arrow = '<i class="icon-right-open-big"></i>';

		/**
		 * All settings setup by magic method __set
		 *
		 * @var array
		 * @access private
		 */
		private $settings = array();

		/**
		 * HTML tag before open <a href> tag
		 *
		 * @var string
		 * @access private
		 */
		private $tag_before_href;

		/**
		 * HTML tag after closing </a> tag
		 *
		 * @var string
		 * @access private
		 */
		private $tag_after_href;

		/**
		 * Prev <a href> css class
		 *
		 * @var string
		 * @access private
		 */
		private $prev_href_class;

		/**
		 * Next <a href> css class
		 *
		 * @var string
		 * @access private
		 */
		private $next_href_class;

		/**
		 * Dots <span> css class
		 *
		 * @var string
		 * @access private
		 */
		private $dots_class;

		/**
		 *
		 * Class constructor
		 *
		 * @access public
		 *
		 * @param null|int $total_page
		 * @param null|int $current_page
		 */
		public function __construct( $total_page = null, $current_page = null ) {
			if ( ! is_null( $total_page ) ) {
				$this->total_pages = $total_page;
			}
			if ( ! is_null( $current_page ) ) {
				$this->current_page = $current_page;
			}
			add_filter( 'paginate_links', array( $this, 'fixUrl' ) );
		}

		/**
		 * Set parameter to paginate_links() function
		 *
		 * @link   http://codex.wordpress.org/Function_Reference/paginate_links#Parameters
		 *
		 * @param string $name
		 * @param mixed  $value
		 *
		 * @return $this
		 * @access public
		 */
		public function __set( $name, $value ) {
			$this->settings[ $name ] = $value;

			return $this;
		}

		/**
		 * Set preview arrow
		 *
		 * @param string $prev_arrow
		 *
		 * @return $this
		 * @access public
		 */
		public function setPrevArrow( $prev_arrow ) {
			$this->prev_arrow = $prev_arrow;

			return $this;
		}

		/**
		 * Set next arrow
		 *
		 * @param string $prev_arrow
		 *
		 * @return $this
		 * @access public
		 */
		public function setNextArrow( $prev_arrow ) {
			$this->next_arrow = $prev_arrow;

			return $this;
		}

		/**
		 *
		 * Set total pages limit
		 *
		 * @param integer $total_posts
		 *
		 * @access public
		 * @return $this
		 */
		public function setTotalPages( $total_posts ) {
			$this->total_pages = $total_posts;

			return $this;
		}

		/**
		 * Set current page
		 *
		 * @param int $current_page
		 *
		 * @return $this
		 * @access public
		 */
		public function setCurrentPage( $current_page ) {
			$this->current_page = $current_page;

			return $this;
		}

		/**
		 * Set HTML tag before opening <a href
		 *
		 * @param string $html_tag
		 *
		 * @return $this
		 * @access public
		 */
		public function setTagBeforeHref( $html_tag ) {
			$this->tag_before_href = $html_tag;

			return $this;
		}

		/**
		 * Set HTML tag after closing </a>
		 *
		 * @param $html_tag
		 *
		 * @return $this
		 * @access public
		 */
		public function setTagAfterHref( $html_tag ) {
			$this->tag_after_href = $html_tag;

			return $this;
		}

		/**
		 * Set css class to prev href
		 *
		 * @param string $css_class
		 *
		 * @return $this
		 * @access public
		 */
		public function setPrevHrefClass( $css_class ) {
			$this->prev_href_class = $css_class;

			return $this;
		}

		/**
		 * Set css class to next href
		 *
		 * @param string $css_class
		 *
		 * @return $this
		 * @access public
		 */
		public function setNextHrefClass( $css_class ) {
			$this->next_href_class = $css_class;

			return $this;
		}

		/**
		 * Set css class to dots span
		 *
		 * @param string $css_class
		 *
		 * @return $this
		 * @access public
		 */
		public function setDotsClass( $css_class ) {
			$this->dots_class = $css_class;

			return $this;
		}

		/**
		 * Get current pager page
		 *
		 * @return $this
		 * @access public
		 */
		public function getCurrentPage() {
			return max( 1, $this->current_page );
		}

		/**
		 *
		 * Get total page count
		 *
		 * @return integer
		 * @access public
		 */
		public function getTotalPages() {
			return $this->total_pages;
		}

		/**
		 * Get prev arrow
		 *
		 * @return string
		 * @access public
		 */
		public function getPrevArrow() {
			$prev_arrow = is_rtl() ? '&rarr;' : $this->prev_arrow;

			return $prev_arrow;
		}

		/**
		 * Get next arrow
		 *
		 * @return string
		 * @access public
		 */
		public function getNextArrow() {
			$prev_arrow = is_rtl() ? '&rarr;' : $this->next_arrow;

			return $prev_arrow;
		}

		/**
		 * Fix for url because I don't know way but in url & char is changed to #038
		 * http://example.com/?page_id=22#038;paged=2
		 *
		 * @param $link
		 *
		 * @return string
		 * @access public
		 */
		public function fixUrl( $link ) {
			return str_replace( '#038;', '&', $link );
		}

		/**
		 *
		 * Display pager links
		 *
		 * @link http://codex.wordpress.org/Function_Reference/paginate_links
		 *
		 * @return array list of all links
		 */
		public function getLinks() {

			$params = array(
				'base'      => $this->getBase(),
				'format'    => $this->getFormat(),
				'total'     => $this->getTotalPages(),
				'prev_text' => $this->getPrevArrow(),
				'next_text' => $this->getNextArrow(),
				'current'   => $this->getCurrentPage(),
				'type'      => 'array',
			);
			$params = wp_parse_args( $params, $this->settings );
			$pager  = array();
			$urls   = \paginate_links( $params );

			foreach ( $urls as $url ) {
				$url     = $this->changeDefaultCss( $url );
				$pager[] = $this->tag_before_href . $url
				           . $this->tag_after_href;
			}

			return $pager;
		}

		/**
		 * Used to reference the url, which will be used to create the paginated links.
		 * The default value '%_%' in 'http://example.com/all_posts.php%_%' is replaced by
		 * 'format' argument (see below).
		 * Default: '%_%'
		 *
		 * @return string
		 * @access
		 */
		protected function getBase() {
			$big  = 999999999;
			$base = str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );

			return $base;
		}

		/**
		 * Used for Pagination structure. The default value is '?page=%#%',
		 * If using pretty permalinks this would be '/page/%#%', where the '%#%'
		 * is replaced by the page number.
		 * Default: '?page=%#%'
		 *
		 * @return string
		 * @access
		 */
		protected function getFormat() {
			if ( get_option( 'permalink_structure' ) ) {
				$format = '/page/%#%';
			} else {
				$format = '?page=%#%';
			}

			return $format;
		}

		/**
		 * WordPress does not support to change default
		 * html/css in paginate_links so
		 * this function replace default css to custom
		 *
		 * @param string $link
		 *
		 * @return string
		 * @access private
		 */
		private function changeDefaultCss( $link ) {
			$search    = $replace = array();
			$search[]  = '\'';
			$replace[] = '"';
			//change class for prev arrow url
			if ( ! empty( $this->prev_href_class ) ) {
				$search[]  = 'prev page-numbers';
				$replace[] = $this->prev_href_class;
			}
			//change class for next arrow url
			if ( ! empty( $this->next_href_class ) ) {
				$search[]  = 'next page-numbers';
				$replace[] = $this->next_href_class;
			}
			//change dots class
			if ( ! empty( $this->dots_class ) ) {
				$search[]  = 'page-numbers dots';
				$replace[] = $this->dots_class;
			}

			$url = str_replace( $search, $replace, $link );

			return $url;
		}

		/**
		 * Convert object to string (display links)
		 *
		 * @return string
		 * @access public
		 */
		public function __toString() {
			return implode( $this->getLinks() );
		}
	}
}