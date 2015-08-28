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

namespace SilverWp\ShortCode\Vc;

use SilverWp\Debug;
use SilverWp\Helper\Control\ControlInterface;
use SilverWp\Interfaces\Core;
use SilverWp\ShortCode\Vc\View\ViewAbstract;
use SilverWp\SingletonAbstract;

if ( ! class_exists( '\SilverWp\ShortCode\Vc\ShortCodeAbstract' ) ) {

	/**
	 * Base visual composer short code class
	 *
	 * @category   WordPress
	 * @package    SilverWp
	 * @subpackage ShortCode\Vc
	 * @author     Michal Kalkowski <michal at silversite.pl>
	 * @copyright  SilverSite.pl (c) 2015
	 * @version    $Revision:$
	 */
	abstract class ShortCodeAbstract
		extends \SilverWp\ShortCode\ShortCodeAbstract implements Core {
        /**
         *
         * Setting handler
         *
         * @var array
         * @access protected
         */
        protected $settings = array();

        /**
         * Controls objects handler
         *
         * @var array
         * @access protected
         */
        protected $controls = array();

	    /**
	     * All arguments passed to WP_Query object
	     *
	     * @var array
	     * @access protected
	     */
	    protected $query_args = array();

	    /**
	     * Debug flag if is true all short code setting will be displayed
	     *
	     * @var bool
	     * @since 0.2
	     * @access protected
	     */
		protected $debug = false;

	    /**
         *
         * Class constructor
         *
         * @throws \SilverWp\ShortCode\Exception
         */
        public function __construct() {
            parent::__construct();
            add_action( 'vc_before_init', array( $this, 'init' ) );
        }

        /**
         * Initialize short code
         *
         * @access public
         */
        public function init() {
            $this->create();
            $this->settings[ 'base' ] = $this->getTagBase();
	        if ( $this->debug ) {
		        Debug::dumpPrint( $this->settings );
	        }
	        vc_map( $this->settings );
        }

        /**
         * Set human readable label
         *
         * @param string $label
         *
         * @return $this
         * @access public
         */
        public function setLabel( $label ) {
            $this->settings[ 'name' ] = $label;

            return $this;
        }

        /**
         * Add control element to setting form
         *
         * @param \SilverWp\Helper\Control\ControlInterface $control
         *
         * @return $this
         * @access public
         */
        public function addControl( ControlInterface $control ) {
            $this->settings[ 'params' ][ ] = $control->getSettings();
            $this->controls[ ]             = $control;

            return $this;
        }

        /**
         *
         * Get all registered controls
         *
         * @return array
         * @access public
         */
        public function getControls() {
            $controls = isset( $this->settings[ 'params' ] ) ? $this->settings[ 'params' ] : array();

            return $controls;
        }

        /**
         *
         * Set short code icon
         * Url to icon image or css class
         *
         * @param string $icon
         *
         * @return $this
         * @access public
         */
        public function setIcon( $icon ) {
            $this->settings[ 'icon' ] = $icon;

            return $this;
        }

        /**
         *
         * Add js file to admin area
         *
         * @param string $file_url
         *
         * @return $this
         * @access public
         */
        public function setAdminEnqueueJs( $file_url ) {
            $this->settings[ 'admin_enqueue_js' ] = $file_url;

            return $this;
        }

        /**
         * Add css file to admin area
         *
         * @param string $file_url
         *
         * @return $this
         * @access public
         */
        public function setAdminEnqueueCss( $file_url ) {
            $this->settings[ 'admin_enqueue_css' ] = $file_url;

            return $this;
        }

        /**
         * Create short code settings
         *
         * @access public
         * @return void
         */
        abstract protected function create();

        /**
         *
         * Set name of view class
         *
         * @param string $view_class_name
         *
         * @return $this
         * @access public
         */
        public function setViewClassName( $view_class_name ) {
            $this->settings[ 'php_class_name' ] = $view_class_name;

            return $this;
        }

        /**
         *
         * Set extra css class
         *
         * @param string $class_name
         *
         * @return $this
         * @access public
         */
        public function setCssClass( $class_name ) {
            $this->settings[ 'class' ] = $class_name;

            return $this;
        }

        /**
         * Set content element
         *
         * @param string $content_element
         *
         * @return $this
         * @access public
         */
        public function setContentElement( $content_element ) {
            $this->settings[ 'content_element' ] = $content_element;

            return $this;
        }

        /**
         *
         * Enable or disable setting form
         *
         * @param bool $show_form true/false
         *
         * @return $this
         * @access public
         */
        public function setShowSettingsForm( $show_form ) {
            $this->settings[ 'show_settings_on_create' ] = (bool) $show_form;

            return $this;
        }

        /**
         *
         * Params with greater weight will be rendered first. (Available from Visual Composer 4.4)
         *
         * @param $weight
         *
         * @return $this
         * @access public
         */
        public function setWeight( $weight ) {
            $this->settings[ 'weight' ] = $weight;

            return $this;
        }

        /**
         * Set category
         *
         * @param $category
         *
         * @return $this
         * @access
         */
        public function setCategory( $category ) {
            $this->settings[ 'category' ] = $category;

            return $this;
        }

        /**
         *
         * Use it to divide your params within groups (tabs)
         *
         * @param string $group
         *
         * @return $this
         * @access public
         */
        public function setGroup( $group ) {
            $this->settings[ 'group' ] = $group;

            return $this;
        }

        /**
         * Show value of param in Visual Composer editor
         *
         * @param string $label
         *
         * @return $this
         * @access public
         */
        public function setAdminLabel( $label ) {
            $this->settings[ 'admin_label' ] = $label;

            return $this;
        }

        /**
         *
         * @param string $view
         *
         * @return $this
         * @access public
         * @link http://wpbakery.freshdesk.com/helpdesk/tickets/10004
         */
        public function setJsView( $view ) {
            $this->settings[ 'js_view' ] = $view;

            return $this;
        }

        /**
         *
         * @param string $class
         *
         * @return $this
         * @access public
         */
        public function setWrapperClass( $class ) {
            $this->settings[ 'wrapper_class' ] = $class;

            return $this;
        }

	    /**
	     * Add new setting argument to WP_Query
	     *
	     * @param string $name
	     * @param string|array $value
	     *
	     * @return $this
	     * @access public
	     */
	    public function addQueryArg( $name, $value ) {
		    $this->query_args[ $name ] = $value;

		    return $this;
	    }

	    /**
         * Render view
         *
         * @param array  $attributes
         * @param string $content
         *
         * @return string
         *
         * @access protected
         */
        protected function render( array $attributes, $content = '' ) {
            if ( isset( $this->settings[ 'php_class_name' ] )
                 && class_exists( $this->settings[ 'php_class_name' ] )
                 && SingletonAbstract::isImplemented(
                     $this->settings[ 'php_class_name' ],
                     '\SilverWp\ShortCode\Vc\View\ViewInterface'
                 )
            ) {
                $view = new $this->settings[ 'php_class_name' ]( $this->settings );
            } else {
                $view = new ViewAbstract( $this->settings );
            }
	        $view->setQueryArgs( $this->query_args );
            $output = $view->output( $attributes, $content );

            return $output;
        }

        /**
         * Set default value for short code attributes
         *
         * @param array $attributes
         * @param array $args
         *
         * @return array
         * @access protected
         */
        protected function setDefaultAttributeValue( array $attributes, $args ) {
            $attributes = shortcode_atts( $attributes, $args );

            return $attributes;
        }

        /**
         * Remove prefix from css class
         *
         * @param string $value
         *
         * @return mixed
         * @access protected
         */
        protected function removeCssClassPrefix( $value ) {
            return str_replace( 'silver-vc-bg-', '', $value );
        }

        /**
         * Build href params from link style, trim all spaces
         *
         * @param string $link
         *
         * @return array
         * @access protected
         */
        protected function buildLink( $link ) {
            $link_array = vc_build_link( $link );
            $trim       = array_map( 'trim', $link_array );

            return $trim;
        }


        /**
         * Convert image Id to url
         *
         * @param int $image_id
         *
         * @return bool|string
         * @access protected
         */
        protected function imageId2Url( $image_id ) {
            $image_url = wp_get_attachment_url( $image_id );

            return $image_url;
        }

        /**
         * Get settings array
         *
         * @return array
         * @access public
         */
        public function getSettings() {
            return $this->settings;
        }

        /**
         * Set short code description
         *
         * @param string $description
         *
         * @return $this
         * @access public
         */
        public function setDescription( $description ) {
            $this->settings[ 'description' ] = $description;

            return $this;
        }

        /**
         * Set short code is container
         *
         * @param bool $is_container
         *
         * @access public
         * @return $this
         */
        public function setIsContainer( $is_container ) {
            $this->settings[ 'is_container' ] = (bool) $is_container;

            return $this;
        }

		/**
		 * @param $controls
		 *
		 * @access public
		 * @return $this
		 */
	    public function setControls( $controls ) {
		    $this->settings[ 'controls' ] = $controls;

		    return $this;
	    }

        /**
         * Prepare short code attributes for view
         *
         * @return array
         * @access public
         */
        protected function prepareAttributes() {
            $controls = $this->controls;
            $elements = array();
            foreach ( $controls as $name => $element ) {
                $elements[ $element->getName() ] = $element->getDefault();
            }

            return $elements;
        }
    }
}