<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Translate.php $
  Last committed: $Revision: 2404 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-02-10 09:58:32 +0100 (Wt, 10 lut 2015) $
  ID: $Id: Translate.php 2404 2015-02-10 08:58:32Z padalec $
 */

namespace SilverWp;

if ( ! class_exists( '\SilverwWp\Translate' ) ) {
    /**
     * Translate class
     *
     * @author Michal Kalkowski <michal at silversite.pl>
     * @version $Id: Translate.php 2404 2015-02-10 08:58:32Z padalec $
     * @category WordPress
     * @package SilverWp
     */
    class Translate {

        /**
         *
         * Register theme text domain and language path
         *
         * @static
         * @access public
         */
        public static function init() {
            load_theme_textdomain( THEME_TEXT_DOMAIN, SILVERWP_PLUGIN_DIR . '/../../languages' );
        }

        /**
         * translate text
         *
         * @param string $message_id
         *
         * @return string
         * @static
         * @access public
         */
        public static function translate( $message_id ) {
            return translate( $message_id, THEME_TEXT_DOMAIN );
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
            esc_html_e( $message_id, THEME_TEXT_DOMAIN );
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
            return esc_html__( $message_id, THEME_TEXT_DOMAIN );
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
            _e( $message_id, THEME_TEXT_DOMAIN );
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
            return esc_attr__( $message_id, THEME_TEXT_DOMAIN );
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
            esc_attr_e( $message_id, THEME_TEXT_DOMAIN );
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
            return _n( $single, $plural, $number, THEME_TEXT_DOMAIN );
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
            return _n_noop( $singular, $plural, THEME_TEXT_DOMAIN );
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
            return _x( $text, $context, THEME_TEXT_DOMAIN );
        }
    }
}