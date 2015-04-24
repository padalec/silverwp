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
namespace SilverWp\Helper\Control;

use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\Helper\Control\ControlAbstract' ) ) {

    /**
     *
     * Base class for controls
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Helper\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     * @abstract
     */
    abstract class ControlAbstract implements ControlInterface {

        /**
         *
         * Control type
         *
         * @var string
         * @access protected
         */
        protected $type;

        /**
         *
         * Array with field settings
         *
         * @var array
         * @access protected
         */
        protected $setting;

        /**
         *
         * Class constructor
         *
         * @param string $control_name
         *
         * @throws \SilverWp\Helper\Control\Exception
         */
        public function __construct( $control_name ) {
            $this->setting[ 'name' ] = $control_name;

            if ( ! isset( $this->type ) ) {
                throw new Exception( Translate::translate( 'Class property $type is required and can\'t be empty.' ) );
            }

            $this->setting[ 'type' ] = $this->type;
        }

        /**
         *
         * Set control name
         *
         * @param string $name
         *
         * @return $this
         * @access public
         */
        public function setName( $name ) {
            $this->setting[ 'name' ] = $name;

            return $this;
        }

        /**
         * Get control name
         *
         * @return string
         * @access public
         */
        public function getName() {
            return $this->setting[ 'name' ];
        }

        /**
         * Set control label
         *
         * @param string $label
         *
         * @access public
         * @return $this
         */
        public function setLabel( $label ) {
            $this->setting[ 'label' ] = $label;

            return $this;
        }

        /**
         *
         * Control default value
         *
         * @param mixed $default
         *
         * @access public
         * @return $this
         */
        public function setDefault( $default ) {
            $this->setting[ 'default' ] = $default;

            return $this;
        }

        /**
         *
         * Set control description
         *
         * @param string $description
         *
         * @access public
         * @return $this
         */
        public function setDescription( $description ) {
            $this->setting[ 'description' ] = $description;

            return $this;
        }

        /**
         *
         * Get all control settings
         *
         * @return array
         * @access public
         */
        public function getSettings() {
            return $this->setting;
        }

        /**
         *
         * Get control value
         *
         * @return string
         * @access public
         */
        public function getValue() {
            $value = $this->value;

            return $value;
        }

        /**
         *
         * Get control setting value
         *
         * @param string $name setting key name
         *
         * @return mixed
         * @access public
         */
        public function getSetting( $name ) {
            if ( array_key_exists( $name, $this->setting ) ) {
                return $this->setting[ $name ];
            }

            return false;
        }

        /**
         *
         * Get default control value
         *
         * @return mixed
         * @access public
         */
        public function getDefault() {
            return $this->getSetting( 'default' );
        }

        /**
         * Set control Dependency
         *
         * @param \SilverWp\Helper\Control\ControlInterface $parent_control
         * @param string $callback_function
         *
         * @return $this
         * @throws \SilverWp\Helper\Control\Exception
         * @access public
         */
        public function setDependency() {

            list( $parent_control, $callback_function ) = func_get_args();

            if ( ! $parent_control instanceof ControlInterface ) {
                throw new Exception(
                    Translate::translate(
                        'First arguments should by instance of \SilverWp\Helper\Control\ControlInterface'
                    )
                );
            }
            $this->setting[ 'dependency' ] = array(
                'field'    => $parent_control->getName(),
                'function' => $callback_function
            );

            return $this;
        }

        /**
         * Set validation rule.
         * Possible rules are: required|alphabet|alphanumeric|numeric|email|url|minlength[n]|maxlength[n].
         *
         * @param string $rule
         *
         * @return $this
         * @access public
         */
        public function setValidation( $rule ) {
            $this->setting[ 'validation' ] = $rule;

            return $this;
        }

        /**
         *
         * @param \SilverWp\Helper\Control\ControlInterface $control
         * @param                                           $callback_function
         *
         * @return $this
         * @access public
         */
        public function setBinding( ControlInterface $control, $callback_function ) {
            $this->setting[ 'binding' ] = array(
                'field'    => $control->getName(),
                'function' => $callback_function,
            );

            return $this;
        }

        /**
         * Remove setting key
         *
         * @param string $name
         *
         * @access public
         */
        public function removeSetting( $name ) {
            unset( $this->setting[ $name ] );
        }

        /**
         *
         * Set control value
         *
         * @param mixed $value
         *
         * @return $this
         * @access public
         */
        public function setValue( $value ) {
            $this->setting[ 'value' ] = $value;

            return $this;
        }
    }
}