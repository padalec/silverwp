<?php

/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/String.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: String.php 2184 2015-01-21 12:20:08Z padalec $
 */

/**
 * Strings helpers
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: String.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage Helper
 */
namespace SilverWp\Helper;

class String {
    /**
     * strip slashes from string
     * @param mixed $value
     * @return mixed
     * @static
     */
    public static function stripslashes( $value ){
        return is_array( $value ) ? 
                    \array_map( 'stripslashes_deep', $value ) : 
                        \stripslashes( $value );
    }
    /**
     * alias to esc_html function
     * @param string $text
     * @return string
     */
    public static function esc_html( $text ) {
        return \esc_html( $text );
    }
    /**
     * alias to esc_attr function
     * @param string $text
     * @return string
     */
    public static function esc_attr( $text ) {
        return \esc_attr( $text );
    }
    /**
     * 
     * @param string $text
     * @return string
     */
    public static function esc_attr__( $text ){
        return \esc_attr__( $text, \THEME_CONTEXT );
    }
    /**
     * 
     * Sanitize a string from user input or from the db. 
     * Checks for invalid UTF-8, Convert single < characters to entity, 
     * strip all tags, remove line breaks, tabs and extra white space, 
     * strip octets.
     * 
     * alias to sanitize_text_field
     * 
     * @link http://codex.wordpress.org/Function_Reference/sanitize_text_field
     * @param string $str
     * @return string
     * 
     */
    public static function sanitize_text_field( $str )
    {
        return sanitize_text_field( $str );
    }
    /**
     * 
     * replace part of a string from array
     * 
     * @param array $array_in array with array( search => replace ): array( '{post_url}' => 'example.com' )
     * @param type $subject string to replaced
     * @return string
     */
    public static function str_replace_from_array( array $array_in, $subject ){
        return str_replace( 
                array_keys( $array_in )
                ,array_values( $array_in )
                ,$subject 
            );
    }
}
