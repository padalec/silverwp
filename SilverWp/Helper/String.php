<?php

/**
 * Strings helpers
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version 0.4
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
     * 
     * replace part of a string from array
     * 
     * @param array $array_in array with array( search => replace ): array( '{post_url}' => 'example.com' )
     * @param type $subject string to replaced
     * @return string
     */
	public static function str_replace_from_array( array $array_in, $subject ) {
		$string = str_replace( array_keys( $array_in ), array_values( $array_in ), $subject );

		return $string;
	}
	/**
	 *
	 * Search file content and get all class names in array
	 *
	 * @link http://stackoverflow.com/questions/7153000/get-class-name-from-file
	 * @param string $file full path to file with file name
	 *
	 * @return array array with founded classes
	 * @static
	 * @access public
	 * @since 0.4
	 */
	public static function getClassNameFromFile( $file ) {
		$php_code	 = \file_get_contents( $file );
		$classes	 = array();
		$namespace	 = '';
		$tokens		 = \token_get_all( $php_code );
		$count		 = \count( $tokens );

		for ( $i = 0; $i < $count; $i ++ ) {
			if ( $tokens[ $i ][ 0 ] === T_NAMESPACE ) {
				for ( $j = $i + 1; $j < $count; $j++ ) {
					if ( $tokens[ $j ][ 0 ] === T_STRING ) {
						$namespace .= '\\' . $tokens[ $j ][ 1 ];
					} elseif ( $tokens[ $j ] === '{' || $tokens[ $j ] === ';' ) {
						break;
					}
				}
			}
			if ( $tokens[ $i ][ 0 ] === T_CLASS ) {
				for ( $j = $i + 1; $j < $count; $j++ ) {
					if ( $tokens[ $j ] === '{' ) {
						$classes[] = $namespace . '\\' . $tokens[ $i + 2 ][ 1 ];
					}
				}
			}
		}
		return \array_unique( $classes );
	}
}
