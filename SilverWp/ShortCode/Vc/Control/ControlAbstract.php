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
/*
 Repository path: $HeadURL: $
 Last committed: $Revision: $
 Last changed by: $Author: $
 Last changed date: $Date: $
 ID: $Id: $
*/
namespace SilverWp\ShortCode\Vc\Control;

use SilverWp\Debug;
use SilverWp\Helper\Control\ControlInterface;
use SilverWp\SingletonAbstract;

if ( ! class_exists( 'SilverWp\ShortCode\Vc\Control\ControlAbstract' ) ) {

    /**
     *
     * Base VisualComposer control setting form class
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage ShortCode\Vc\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    abstract class ControlAbstract extends \SilverWp\Helper\Control\ControlAbstract implements ControlInterface {
        public function __construct( $name ) {
            parent::__construct( $name );
            $this->setName( $name );
        }

        /**
         * Set element name
         *
         * @param string $name
         *
         * @return $this
         * @access public
         */
        public function setName( $name ) {
            $this->removeSetting( 'name' );
            $this->setting[ 'param_name' ] = $name;

            return $this;
        }

        /**
         * Define param visibility depending on other field value
         *
         * @param \SilverWp\Helper\Control\ControlInterface $control
         * @param array                                     $value
         * @param bool                                      $not_empty
         * @param string                                    $callback_js
         *
         * @return $this
         * @access public
         */
        public function setDependency() {
            $args = func_get_args();
            if ( is_object( $args[ 0 ] )
                 && SingletonAbstract::isImplemented( $args[ 0 ], '\SilverWp\Helper\Control\ControlInterface' )
            ) {
                $dependency[ 'element' ] = $args[ 0 ]->getName();
            } elseif ( is_string( $args[ 0 ] ) ) {
                $dependency[ 'element' ] = $args[ 0 ];
            }
            $dependency = array(
                'value'     => $args[ 1 ],
                'not_empty' => isset( $args[ 2 ] ) ? $args[ 2 ] : false,
            );

            if ( isset( $args[ 3 ] ) ) {
                $dependency[ 'callback' ] = $args[ 3 ];
            }

            $this->setting[ 'dependency' ] = $dependency;

            return $this;
        }

        /**
         * Class name that will be added to the "holder" HTML tag.
         * Useful if you want to target some CSS rules to specific items in the backend edit interface
         *
         * @param string $css_class
         *
         * @return $this
         * @access public
         */
        public function setCssClass( $css_class ) {
            $this->setting[ 'class' ] = $css_class;

            return $this;
        }

        /**
         * Set param container width in content element edit window.
         * According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
         *
         * @param string $edit_field_class
         *
         * @return $this
         * @access public
         */
        public function setEditFieldClass( $edit_field_class ) {
            $this->setting[ 'edit_field_class' ] = $edit_field_class;

            return $this;
        }

        /**
         * Params with greater weight will be rendered first. (Available from Visual Composer 4.4)
         *
         * @param int $weight
         *
         * @return $this
         * @access public
         */
        public function setWeight( $weight ) {
            $this->setting[ 'weight' ] = $weight;

            return $this;
        }

        /**
         * Use it to divide your params within groups (tabs)
         *
         * @param string $group
         *
         * @return $this
         * @access public
         */
        public function setGroup( $group ) {
            $this->setting[ 'group' ] = $group;

            return $this;
        }

        /**
         * HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode.
         * Default: hidden input
         *
         * @param string $holder
         *
         * @return $this
         * @access public
         */
        public function setHolder( $holder ) {
            $this->setting[ 'holder' ] = $holder;

            return $this;
        }

        /**
         * Get element name
         *
         * @return string|null
         * @access public
         */
        public function getName() {
            $name = $this->getSetting( 'param_name' );

            return $name;
        }

        /**
         * Show value of param in Visual Composer editor
         *
         * @param boolean $label
         *
         * @return $this
         * @access pubic
         */
        public function setAdminLabel( $label ) {
            $this->setting[ 'admin_label' ] = $label;

            return $this;
        }

        /**
         * Set param_holder_class attribute
         *
         * @param string $css_class
         *
         * @return $this
         * @access public
         */
        public function setParamHolder( $css_class ) {
            $this->setting[ 'param_holder_class' ] = $css_class;

            return $this;
        }

        /**
         * Get default value of element
         *
         * @return mixed|null
         * @access public
         */
        public function getDefault() {
            $default = $this->getSetting( 'value' );

            return $default;
        }

        /**
         * Set default value alias to setValue
         *
         * @param mixed $value
         *
         * @return $this
         * @access public
         */
        public function setDefault( $value ) {
            $this->setValue( $value );

            return $this;
        }

        /**
         * Set label
         *
         * @param string $label
         *
         * @return $this
         * @access public
         */
        public function setLabel( $label ) {
            $this->setting[ 'heading' ] = $label;

            return $this;
        }
    }
}