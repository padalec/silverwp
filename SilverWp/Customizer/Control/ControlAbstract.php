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
namespace SilverWp\Customizer\Control;

use SilverWp\Debug;
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
     * @copyright SilverSite.pl 2015
     * @version $Revision:$
     * @link http://kirki.org/
     * @abstract
     */
    abstract class ControlAbstract extends \SilverWp\Helper\Control\ControlAbstract implements ControlInterface {

        /**
         * Export or not to less variable
         *
         * @var bool
         * @access protected
         */
        protected $is_template_variable = true;

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
         * @link http://kirki.org/#getting-the-value-of-a-background-control
         */
        public function getValue() {

//            $value = get_theme_mod( $this->getName(), $this->getDefault() );
            $value = kirki_get_option( $this->getName() );
            return $value;
        }

	    /**
	     * Set control Dependency
	     *
	     * @return $this
	     * @throws \SilverWp\Customizer\Control\Exception
	     * @access public
	     */
	    public function setDependency() {
		    list( $parent_control, $operator, $parent_option ) = func_get_args();

		    if ( ! $parent_control instanceof ControlInterface ) {
			    throw new Exception(
				    Translate::translate(
					    'First arguments should by instance of \SilverWp\Customizer\Control\ControlInterface'
				    )
			    );
		    }
		    $this->setting['required'][] = array(
			    'setting'  => $parent_control->getName(),
			    'operator' => $operator,
			    'value'    => $parent_option,
		    );

		    return $this;
	    }

	    /**
	     * Add dependency
	     *
	     * @param ControlInterface $parent_control
	     * @param string $operator example: ==, !== etc.
	     * @param string $parent_option
	     *
	     * @return $this
         * @see https://github.com/aristath/kirki/wiki/required
	     */
	    public function addDependency( ControlInterface $parent_control, $operator, $parent_option ) {
		    $this->setting['required'][] = array(
			    'setting'  => $parent_control->getName(),
			    'operator' => $operator,
			    'value'    => $parent_option,
		    );

		    return $this;
	    }

        /**
         * Using the output argument you can specify if you want Kirki to automatically
         * generate and apply CSS for various elements of your page.
         * This is defined as an array of arrays and each array contains a CSS element,
         * a CSS property, and - optionally - a unit.
         * Based on these values Kirki will then automatically generate the necessary
         * CSS and properly enqueue it to the <head> of your document so that your changes
         * take effect immediately without the need to write any additional code.
         * As element you define a CSS element in your document that you want to affect.
         * As property you can use any valid CSS property.
         * As units you can use any valid CSS unit (for example px, em, rem etc.)
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
         * @see https://github.com/aristath/kirki/wiki/output
         */
        public function setOutput( array $output ) {
            $this->setting[ 'output' ] = $output;

            return $this;
        }

        /**
         * Add output element
         *
         * @param string $element as you define a CSS element in your document that you want to affect.
         * @param string $property as you can use any valid CSS property.
         * @param null|string $units as units you can use any valid CSS unit (for example px, em, rem etc.)
         *
         * @return $this
         * @access public
         * @see https://github.com/aristath/kirki/wiki/output
         */
        public function addOutput( $element, $property, $units = null ) {
            $output = array(
                'element'  => $element,
                'property' => $property,
            );
            if ( ! is_null( $units ) ) {
                $output[ 'units' ] = $units;
            }
            $this->setting[ 'output' ][ ] = $output;

            return $this;
        }

        /**
         *
         * Get value of this field is for less or not
         *
         * @return bool
         * @access public
         */
        public function isTemplateVariable() {
            return $this->is_template_variable;
        }

        /**
         *
         * Value from this field will be available in css template
         *
         * @param bool $is_template_variable true/false
         *
         * @return $this
         * @access public
         */
        public function setIsTemplateVariable( $is_template_variable ) {
            $this->is_template_variable = (bool) $is_template_variable;

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
         * Help, like description can be any string you want.
         * If you add some help text here, it will be added on a tooltip
         * that users can see on hover. It is an easy way to provide
         * additional information and help without cluttering the screen.
         *
         * @param string $message
         *
         * @return $this
         * @access public
         * @see https://github.com/aristath/kirki/wiki/help
         */
        public function setHelp( $message ) {
            $this->setting[ 'help' ] = $message;

            return $this;
        }

        /**
         *
         * If you set transport to postMessage you can write your own scripts,
         * or you can use the js_vars argument and let Kirki automatically create these for you.
         *
         * It is defined as an array of arrays so you can specify multiple elements.
         * 'ELEMENT'
         * The CSS element you want to affect
         *
         * 'FUNCTION'
         * Can be 'css' or 'html'.
         *
         * 'PROPERTY'
         * If you set 'function' to 'css' then this will allow you to select what
         * CSS you want applied to the selected 'element'.
         *
         * @param array $variable
         *
         * @return $this
         * @access public
         * @see https://github.com/aristath/kirki/wiki/js_vars
         */
        public function addJsVariable( array $variable ) {
            $this->setting[ 'js_vars' ][ ] = $variable;

            return $this;
        }
    }
}