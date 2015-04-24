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
namespace SilverWp\Customizer\Control;

use SilverWp\Debug;
use SilverWp\Helper\Control\ControlInterface;
use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\Customizer\Control\ControlAbstract' ) ) {

    /**
     *
     * Base class for customizer controls
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Customizer\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     * @link http://kirki.org/
     * @abstract
     */
    abstract class ControlAbstract extends \SilverWp\Helper\Control\ControlAbstract implements \SilverWp\Customizer\Control\ControlInterface {

        /**
         *
         * @var bool
         * @access protected
         */
        protected $is_less_variable = true;

        /**
         *
         * Class constructor
         *
         * @param string $control_name
         *
         * @throws \SilverWp\Customizer\Control\Exception
         */
        public function __construct( $control_name ) {
            parent::__construct( $control_name );
            $this->setName( $control_name );
        }

        /**
         * Set section name when control belong to
         *
         * @param string $name
         *
         * @return $this
         * @access public
         */
        public function setSectionName( $name ) {
            $this->setting[ 'section' ] = $name;

            return $this;
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
            $this->removeSetting( 'name' );
            $this->setting[ 'settings' ] = $name;

            return $this;
        }

        /**
         * Get control name
         *
         * @return string
         * @access public
         */
        public function getName() {
            return $this->setting[ 'settings' ];
        }

        /**
         *
         * Control priority
         *
         * @param int $priority
         *
         * @access public
         * @return $this
         */
        public function setPriority( $priority ) {
            $this->setting[ 'priority' ] = $priority;

            return $this;
        }

        /**
         *
         * Set control sub title
         *
         * @param string $subtitle
         *
         * @access public
         * @return $this
         */
        public function setSubtitle( $subtitle ) {
            $this->setDescription( $subtitle );
            return $this;
        }

        /**
         *
         * Get control value
         *
         * @return string
         * @access public
         */
        public function getValue() {
            $value = get_theme_mod( $this->getName(), $this->getDefault() );

            return $value;
        }

        /**
         * Set control Dependency
         *
         * @param \SilverWp\Helper\Control\ControlInterface $parent_control parent control
         * @param string                                    $parent_option parent option value
         *
         * @return $this
         * @access public
         */
        public function setDependency( ControlInterface $parent_control, $parent_option ) {
            $this->setting[ 'required' ] = array(
                $parent_control->getName() => $parent_option
            );

            return $this;
        }

        /**
         * When the 'output' argument is used, we will automatically process this and generate the necessary CSS for your page.
         * You can specify an array of arrays so that you can affect multiple elements at once.
         * You will need to define the CSS element you want to change, the CSS property, and optionally a unit.
         *
         * @param array $output example:
         * array(
         *      array(
         *          'element'  => '#content .btn',
         *          'property' => 'color'
         *          Units can be useful when defining for example font-size
         *          They can be anything you want appended to the value
         *          but usually it's something like 'px', 'em', 'rem' etc.
         *          'units'    => '',
         *      ),
         *      array(
         *          'element'  => '#content',
         *          'property' => 'border-color',
         *      ),
         * ),
         *
         * @access public
         * @return $this
         */
        public function setOutput( array $output ) {
            $this->setting[ 'output' ] = $output;

            return $this;
        }

        /**
         *
         * Get value of this field is for less or not
         *
         * @return bool
         * @access public
         */
        public function getIsLessVariable() {
            return $this->is_less_variable;
        }

        /**
         *
         * Set this field will be added to less variables
         *
         * @param bool $is_less
         *
         * @return $this
         * @access public
         */
        public function setIsLessVariable( $is_less ) {
            $this->is_less_variable = $is_less ? true : false;

            return $this;
        }

        /**
         *
         * If you've written a script that will auto-refresh the preview using ajax
         * you can set this to 'postMessage'.
         * Read http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/ for details.
         *
         * @param string $transport 'refresh' or 'postMessage'
         *
         * @access public
         * @return $this
         */
        public function setTransport( $transport ) {
            $this->setting[ 'transport' ] = $transport;

            return $this;
        }

        /**
         *
         * If we have a custom function for sanitizing this field then we can use that here.
         * By default all fields are properly sanitized in Kirki.
         * However, in some cases you may want to override these defaults (for example when using a textarea to paste a google-ad)
         *
         * @param array|string $callback
         *
         * @return $this
         * @access public
         */
        public function setSanitizeCallback( $callback ) {
            $this->setting[ 'sanitize_callback' ] = $callback;

            return $this;
        }

        /**
         *
         * You may add extra info in the help area
         *
         * @param string $message
         *
         * @return $this
         * @access public
         * @link https://github.com/aristath/kirki/issues/82#issuecomment-85444685
         */
        public function setHelp( $message ) {
            $this->setting[ 'help' ] = $message;

            return $this;
        }
    }
}