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

use SilverWp\Debug;
use SilverWp\FileSystem;

if ( ! class_exists( '\SilverWp\ShortCode\Vc\Control\Slider' ) ) {

    /**
     * Slider
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage ShortCode\Form\Element
     * @author Michal Kalkowski <michal at dynamite-studio.pl>
     * @copyright Dynamite-Studio.pl 2014
     * @version $Id: Slider.php 2337 2015-02-04 13:53:23Z padalec $
     */
    class Slider extends NewControlAbstract {
        protected $type = 'slider';

        /**
         * Enqueue css
         *
         * @access private
         */
        public function adminEnqueueStyle() {
	        $assets_uri = FileSystem::getDirectory( 'assets_uri' );
	        wp_enqueue_style(
                'jQueryUi',
	            $assets_uri . 'css/admin/jqueryui/smoothness/jquery-ui-1.9.2.custom.min.css'
            );
        }

        /**
         * Add javascript file
         *
         * @return string
         * @access string
         */
        protected function getJsScript() {
            return FileSystem::getDirectory( 'assets_uri' ) . 'js/admin/vc_slider.js';
        }

        /**
         * Create new setting form element
         *
         * @param array $settings
         * @param mixed $value
         *
         * @return string
         * @access public
         */
        public function createControl( array $settings, $value ) {
            $name  = esc_attr( $this->getName() );
            $min   = esc_attr( $settings[ 'min' ] );
            $max   = esc_attr( $settings[ 'max' ] );
            $step  = esc_attr( $settings[ 'step' ] );
            $value = esc_attr( $value );

            $css_class = $this->getCssClass();

            $html = '<div class="ds-vc-slider" data-min="' . $min . '" data-max="' . $max . '" data-step="' . $step . '" data-default="' . $value . '"></div>';
            $html .= '<input class="' . $css_class . '" type="text" value="" name="' . $name . '"  />';

            return $html;
        }

        /**
         * Set min range
         *
         * @param int $min
         *
         * @access public
         * @return $this
         */
        public function setMin( $min ) {
            $this->setting[ 'min' ] = (int) $min;

            return $this;
        }

        /**
         * Set max range
         *
         * @param int $max
         *
         * @access public
         * @return $this
         */
        public function setMax( $max ) {
            $this->setting[ 'max' ] = (int) $max;

            return $this;
        }

        /**
         * Set slider steps
         *
         * @param int $step
         *
         * @access public
         * @return $this
         */
        public function setStep( $step ) {
            $this->setting[ 'step' ] = (int) $step;

            return $this;
        }
    }
} 