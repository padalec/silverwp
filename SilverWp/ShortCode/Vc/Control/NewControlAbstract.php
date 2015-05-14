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

if ( ! class_exists( 'SilverWp\ShortCode\Vc\Control\NewControlAbstract' ) ) {

    /**
     * Visual composer jQuery UI Slider element
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage ShortCode\Vc\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     * @abstract
     * @link https://wpbakery.atlassian.net/wiki/display/VC/Create+New+Param+Type
     */
    abstract class NewControlAbstract extends ControlAbstract implements NewControlInterface {

        public function __construct( $name ) {
            parent::__construct( $name );
            $this->setName( $name );
            $class = get_called_class();

            if ( method_exists( $class, 'adminEnqueueStyle' ) && is_admin() ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueStyle' ) );
            }

            $script_url = null;
            if ( method_exists( $class, 'getJsScript' ) ) {
                $script_url = $this->getJsScript();
            }

            if ( function_exists( 'vc_add_shortcode_param' ) ) {
                if ( ! vc_add_shortcode_param( $this->type, array( $this, 'createControl' ), $script_url ) ) {
                    throw new Exception( Translate::translate( 'Can\'t create element ' . $this->type ) );
                }
            }
        }

        /**
         * Init object
         *
         * @param array $settings
         * @param mixed $value
         *
         * @access protected
         */
        protected function init( array $settings, $value ) {
            $this->setName( $settings[ 'param_name' ] );
            $this->setValue( $value );
        }

        /**
         * TODO implement this method before fire createControl should start init method
         *
         * @param $method_name
         * @param $attributes
         *
         * @access public
         */
        public function __call( $method_name, $attributes ) {
            silverwp_debug_var( $method_name );
            silverwp_debug_var( $attributes );
            if ( $method_name == 'createControl' ) {
                $settings = $attributes[ 0 ];
                $value    = $attributes[ 1 ];
                $this->init( $settings, $value );
                $this->createControl( $settings, $value );
            }
        }

        /**
         * Format css class name for input this is required by VC
         *
         * @param string $input_type
         *
         * @return string
         * @access protected
         */
        protected function getCssClass( $input_type = 'textinput' ) {
            $css_class = 'wpb_vc_param_value wpb-' . $input_type . ' ';
            $css_class .= esc_attr( $this->getName() ) . ' ' . esc_attr( $this->type ) . '_field';

            return $css_class;
        }
    }
}