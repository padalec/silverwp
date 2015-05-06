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
namespace SilverWp;

if ( ! class_exists( '\SilverWp\Translate' ) ) {
    /**
     * Translate class
     *
     * @author Michal Kalkowski <michal at silversite.pl>
     * @version $Revision:$
     * @category WordPress
     * @package SilverWp
     */
    class Translate {

        /**
         * Path to languages files
         *
         * @var string
         */
        public static $language_path =  '/../languages';

        /**
         *
         * Language text domain
         *
         * @var string
         */
        public static $text_domain = SILVERWP_THEME_TEXT_DOMAIN;
        /**
         *
         * Register theme text domain and language path
         *
         * @static
         * @access public
         */
        public static function init() {

            load_theme_textdomain( self::$text_domain, self::$language_path);
        }

        /**
         * Translate text
         *
         * @return string
         * @static
         * @access public
         */
        public static function translate() {
            $args_count = func_num_args();
            $args       = func_get_args();
            $message_id = array_shift( $args );
            if ($args_count > 1) {
                $message = vsprintf( translate( $message_id, self::$text_domain ), $args );
            } else {
                $message = translate( $message_id, self::$text_domain );
            }
            return $message;
        }

        /**
         *
         * escaping from html string
         *
         * @param string $message_id
         *
         * @static
         * @access public
         */
        public static function escHtmlE( $message_id ) {
            esc_html_e( $message_id, self::$text_domain );
        }

        /**
         * Retrieve the translation of $message_id and escapes it for safe use in HTML output.
         * If there is no translation, or the text domain isn't loaded, the original text is returned.
         * alias to esc_html__ function
         *
         * @param string $message_id
         *
         * @return string
         * @static
         * @access public
         */
        public static function escHtml( $message_id ) {
            return esc_html__( $message_id, self::$text_domain );
        }

        /**
         *
         * alias to _e() function
         *
         * @param string $message_id
         *
         * @static
         * @access public
         */
        public static function e( $message_id ) {
            _e( $message_id, self::$text_domain );
        }

        /**
         * alias to esc_attr__ function
         *
         * @param string $message_id
         *
         * @return string
         * @static
         */
        public static function escAttr( $message_id ) {
            return esc_attr__( $message_id, self::$text_domain );
        }

        /**
         *
         * alias to esc_attr_e function
         *
         * @param string $message_id
         *
         * @static
         */
        public static function escAttrE( $message_id ) {
            esc_attr_e( $message_id, self::$text_domain );
        }

        /**
         *
         * alias to _n() function
         *
         * @param string $single
         * @param string $plural
         * @param int    $number
         *
         * @return string
         */
        public static function n( $single, $plural, $number ) {
            return _n( $single, $plural, $number, self::$text_domain );
        }

        /**
         * translate message with reserved places
         *
         * @return string
         */
        public static function params() {
            $args       = func_get_args();
            $message_id = array_shift( $args );

            return vsprintf( self::translate( $message_id ), $args );
        }

        /**
         * Register plural strings in POT file, but don't translate them.
         *
         * Used when you want to keep structures with translatable plural
         * strings and use them later.
         *
         * Example:
         * <code>
         * $messages = array(
         *    'post' => _n_noop('%s post', '%s posts'),
         *    'page' => _n_noop('%s pages', '%s pages')
         * );
         * ...
         * $message = $messages[$type];
         * $usable_text = sprintf( translate_nooped_plural( $message, $count ), $count );
         * </code>
         *
         * @since 1.7
         *
         * @param string $singular Single form to be i18ned.
         * @param string $plural Plural form to be i18ned.
         *
         * @return array array($singular, $plural)
         */
        public static function nNoop( $singular, $plural ) {
            return _n_noop( $singular, $plural, self::$text_domain );
        }

        /**
         *
         * @see _x()
         * @param string $text
         * @param string $context
         *
         * @static
         * @return string|void
         * @access public
         */
        public static function x($text, $context) {
            return _x( $text, $context, self::$text_domain );
        }
    }
}