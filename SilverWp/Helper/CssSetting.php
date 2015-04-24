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

/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/CssSetting.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: CssSetting.php 2184 2015-01-21 12:20:08Z padalec $
 */

namespace SilverWp\Helper;

use SilverWp\SingletonAbstract;

/**
 * This class get CSS setting from css template file with defined
 * styles and replace parametrs from Theme Options
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: CssSetting.php 2184 2015-01-21 12:20:08Z padalec $
 * @category SilverWp
 * @package Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
class CssSetting extends SingletonAbstract
{
    /**
     * template file name
     *
     * @var string
     * @access private
     */
    private $_css_template = 'theme-custom.css';
    /**
     * PECL pattern for read ale variable from css
     *
     * @var string
     * @access private
     */
    private $_pattern = '/[\@](.*?)[\;^\)^\}]/';
    /**
     * css string
     *
     * @var string
     */
    private $_css = null;
    /**
     * list of all foundet variable
     *
     * @var array
     * @access private
     * @static
     */
    private static $_css_variable = array();
    /**
     *
     * @var array
     * @static
     */
    public static $_fonts = array();
    /**
     *
     * class constructor
     *
     * @access protected
     */
    protected function __construct()
    {
        $this->_css = \file_get_contents( $this->_get_template_file() );
    }

    /**
     *
     * full path to css template file
     *
     * @return string
     * @access protected
     */
    protected function _get_template_file()
    {
        return SILVERWP_THEME_PATH . '/' . $this->_css_template;
    }
    /**
     * default font family
     *
     * @return string
     */
    public function get_default_fonts_family()
    {
        return silverwp_get_default_font_family();
    }
    /**
     * get layout settings
     *
     * @return array
     */
    public function set_layout()
    {
        $styles = Option::get_theme_option( 'style_layout', true );
        return $this->_set_value( $styles );
    }
    /**
     * get background settings
     *
     * @return array
     */
    public function set_background()
    {
        $styles = Option::get_theme_option( 'style_background', true );
        if ( $styles['scroll_attachment'] == '1' ) {
            $styles['body-bg-size'] = 'auto';
        }else{
            $styles['body-bg-size'] = 'cover';
        }
        if( !empty( $styles['body-bg-img'] ) ){
            $styles['body-bg-img'] = 'background-image:url('.$styles['body-bg-img'].');';
        }else{
            $styles['body-bg-img'] = '';
        }
        return $this->_set_value( $styles );
    }
    /**
     * get fonts settings
     *
     * @return array
     */
    public function set_font()
    {
        $fonts_body = Option::get_theme_option( 'style_fonts_body', true );
        unset( $fonts_body['preview'] );
        $fonts_heading = Option::get_theme_option( 'style_fonts_heading', true );
        unset( $fonts_heading['preview'] );
        $fonts_main_menu = Option::get_theme_option( 'style_fonts_main_menu', true );
        unset( $fonts_main_menu['preview'] );

        self::$_fonts = array_merge( $fonts_body, $fonts_heading, $fonts_main_menu );
        foreach (self::$_fonts as $key => $value) {
            if (strpos($key, 'family')) {
                $font = explode(',', trim($value, ','));
                $font[0] = strpos($font[0], ' ') ? '\'' . $font[0] . '\'' : $font[0];
                self::$_fonts[ $key ] = implode(',', $font);
            }
        }
        $this->_set_value( self::$_fonts );

        return self::$_css_variable;
    }
    /**
     * get all founded fonts
     *
     * @return array
     * @access public
     */
    public function get_fonts()
    {
        $fonts = array();
        foreach( self::$_fonts as $key => $value ) {
            if( strpos( $key, 'family')){
                $name = $this->_extract_font( $value );
            }
            if ( strpos( $key, 'weight' ) ) {
                $fonts[ $name ]['weight'][] = empty( $value ) ? 'normal' : $value;
            }
            if ( strpos( $key, 'style' ) ) {
                $fonts[ $name ]['style'][] = empty( $value ) ? 'normal' : $value;
            }
            if ( strpos( $key, 'subset' ) ) {
                $fonts[ $name ]['subset'][] = $value[0];
            }
            if ( strpos( $key, 'type' ) ) {
                $fonts[ $name ]['type'] = $value;
            }
        }

        return $fonts;
    }
    /**
     *
     * read all varibales from css file and add to array
     *
     * @return array
     * @access private
     */
    private function _get_variables()
    {
        $matches = array();
        preg_match_all( $this->_pattern, $this->_css, $matches, PREG_PATTERN_ORDER );
        return $matches[1];
    }
    /**
     *
     * merge variable with values from theme options
     *
     * @param array $array_values
     * @return array
     */
    private function _set_value(array $array_values)
    {
        $variables = $this->_get_variables();
        foreach ( $variables as $i => $css_name ) {
            foreach ( $array_values as $key => $value ) {
                if ( $css_name == $key ) {
                    self::$_css_variable[ '{@'.$css_name.'}' ] = $value;
                }
            }
        }
        return self::$_css_variable;
    }
    /**
     *
     * replace all founded varibale
     * for his values
     *
     * @return string
     * @access public
     */
    public function replace()
    {
        return str_replace(
            array_keys( self::$_css_variable )
            ,array_values( self::$_css_variable )
            , $this->_css
        );
    }
    /**
     * extract main font name form all fonts string
     *
     * @param string $fonts_family
     * @return string font name
     * @access private
     */
    private function _extract_font($fonts_family)
    {
        $font_face = explode( ',', trim( $fonts_family, ',' ) );
        return $font_face[0];
    }
}
