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
 Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ShortCode/Form/Element/Fontcontainer.php $
 Last committed: $Revision: 2184 $
 Last changed by: $Author: padalec $
 Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
 ID: $Id: Fontcontainer.php 2184 2015-01-21 12:20:08Z padalec $
*/
namespace SilverWp\ShortCode\Vc\Control;

if ( ! class_exists( '\SilverWp\ShortCode\Vc\Form\Element\FontContainer' ) ) {

    /**
     * Visual composer settings form element fonts container.
     * Example :
     * array(
     *        'type' => 'font_container',
     *        'param_name' => 'font_container',
     *        'value'=>'',
     *        'settings' => array(
     *            'fields' => array(
     *
     *                'tag'=>'h2', // default value h2
     *                'text_align',
     *                'font_size',
     *                'line_height',
     *                'color',
     *                //'font_style_italic'
     *                //'font_style_bold'
     *                //'font_family'
     *
     *                'tag_description' => __('Select element tag.','js_composer'),
     *                'text_align_description' => __('Select text alignment.','js_composer'),
     *                'font_size_description' => __('Enter font size.','js_composer'),
     *                'line_height_description' => __('Enter line height.','js_composer'),
     *                'color_description' => __('Select color for your element.','js_composer'),
     *                //'font_style_description' => __('Put your description here','js_composer'),
     *                //'font_family_description' => __('Put your description here','js_composer'),
     *            ),
     *        ),
     *        // 'description' => __( '', 'js_composer' ), // description for field group
     *    ),
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage ShortCode\Form\Element
     * @author Michal Kalkowski <michal at dynamite-studio.pl>
     * @copyright Dynamite-Studio.pl 2014
     * @version $Id: Fontcontainer.php 2184 2015-01-21 12:20:08Z padalec $
     */
    class FontContainer extends ControlAbstract {
        protected $type = 'font_container';

        /**
         *
         * Magic method __set. Passable keys:
         *
         *  'tag'=>'h2', // default value h2
         *  'text_align',
         *  'font_size',
         *  'line_height',
         *  'color',
         *  'font_style_italic'
         *  'font_style_bold'
         *  'font_family'
         *
         * 'tag_description' => __('Select element tag.','js_composer'),
         * 'text_align_description' => __('Select text alignment.','js_composer'),
         * 'font_size_description' => __('Enter font size.','js_composer'),
         * 'line_height_description' => __('Enter line height.','js_composer'),
         * 'color_description' => __('Select color for your element.','js_composer'),
         * 'font_style_description' => __('Put your description here','js_composer'),
         * 'font_family_description' => __('Put your description here','js_composer'),
         *
         * @param string $name fields settings name
         * @param mixed  $value fields settings value
         *
         * @access public
         * @link http://php.net/manual/en/language.oop5.overloading.php#object.set
         */
        public function __set( $name, $value ) {
            $this->setting[ 'settings' ][ 'fields' ][ $name ] = $value;
        }

        /**
         * Set settings key
         *
         * @param array $settings
         *
         * @return $this
         * @access public
         */
        public function setSettings( array $settings ) {
            $this->setting[ 'settings' ] = $settings;

            return $this;
        }

    }
} 