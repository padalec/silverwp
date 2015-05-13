<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/RecursiveArray.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: RecursiveArray.php 2184 2015-01-21 12:20:08Z padalec $
 */

namespace SilverWp\Helper;

use InvalidArgumentException;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * All Recursive Array helper methods
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: RecursiveArray.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
class RecursiveArray {
    /**
     *
     * recursive serch a kay exists in array
     *
     * @param string $needle - search key
     * @param array  $haystack - array to search
     *
     * @return boolean true if $needle is founded
     * @static
     */
    public static function searchKey( $needle, array $haystack ) {
        $result = \array_key_exists( $needle, $haystack );
        if ( $result ) {
            return $result;
        }
        foreach ( $haystack as $v ) {
            if ( \is_array( $v ) ) {
                $result = self::searchKey( $needle, $v );
            }
            if ( $result ) {
                return $result;
            }
        }

        return $result;
    }

    /**
     *
     * Recursive search array
     *
     * @param array  $array_in - array to search
     * @param string $search_key - search key name
     *
     * @return array array with values from searched key
     * @static
     * @link http://stackoverflow.com/a/3975706/995035
     */
    public static function searchIterator( array $array_in, $search_key ) {
        $array_value = array();
        if ( $array_in && \count( $array_in ) ) {
            try {
                $iterator  = new RecursiveArrayIterator( $array_in );
                $recursive = new RecursiveIteratorIterator(
                    $iterator,
                    RecursiveIteratorIterator::SELF_FIRST
                );

                foreach ( $recursive as $key => $value ) {
                    if ( $key == $search_key ) {
                        $array_value[ ] = $value;
                    }
                }

            } catch ( InvalidArgumentException $exc ) {
                $error = $exc->getMessage();
                $error .= $exc->getTraceAsString();
                silverwp_debug_array( $error );
            }
        }

        return $array_value;
    }

    /**
     *
     * recursive search array
     *
     * @param array  $array - array to search
     * @param string $value - search value
     *
     * @return array
     */
    public static function searchRecursive( $array, $value ) {
        $found = array();
        if ( \array_key_exists( $value, $array ) ) {
            return $array[ $value ];
        }
        \array_walk_recursive(
            $array,
            function ( $item, $key ) use ( $value, &$found ) {
                if ( $value === $key ) {
                    $found[ ] = $item;
                }
            }
        );
        return $found;
    }

    /**
     * Search an element in haystack and return key
     *
     * @param array $haystack
     * @param string $needle
     *
     * @return bool|int|string
     * @static
     * @access public
     */
    public static function search( array $haystack, $needle ) {
        foreach ( $haystack as $key => $value ) {
            $current_key = $key;
            if ( $needle === $value OR ( is_array( $value ) && self::search( $value, $needle ) !== false ) ) {
                return $current_key;
            }
        }

        return false;
    }

    /**
     * Remove $needle from $haystack
     *
     * @param array        $haystack
     * @param string|array $needle
     *
     * @return array
     */
    public static function removeByValue( array $haystack, $needle ) {

        if ( is_array( $needle ) ) {
            foreach ( $needle as $value ) {
                if ( ( $key = self::search( $haystack, $value ) ) !== false ) {
                    unset( $haystack[ $key ] );
                }
            }
        } else {
            if ( ( $key = self::search( $haystack, $needle ) ) !== false ) {
                unset( $haystack[ $key ] );
            }
        }

        return $haystack;
    }

    /**
     *
     * remove ale null or empty value from array
     *
     * @param array $haystack array to move empty value
     *
     * @return array array without empty value
     * @static
     * @access public
     */
    public static function removeEmpty( array &$haystack ) {
        $array_out = array();

        foreach ( $haystack as $key => $value ) {
            if ( \is_array( $value ) ) {
                if ( $value !== array() ) {
                    $value = self::removeEmpty( $value );
                }
            } else {
                // Strip whitespace from the beginning and end of a string
                $value = \trim( $value );
                //fix bug Ticket #279 delete b letter in brandprimary
                $value = \str_replace( '&nbsp;', '', $value );
            }

            if ( $value !== '' && $value !== null && $value !== array() ) {
                if ( \is_array( $value ) ) {
                    $array_out[ $key ] = self::removeEmpty( $value );
                } else {
                    $array_out[ $key ] = $value;
                }
            }
        }

        return $array_out;
    }
    /**
     * Returns the values recursively from columns of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                         a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                         the returned array. This value may be the integer key
     *                         of the column, or it may be the string key name.
     *
     * @return array
     * @link http://php.net/manual/en/function.array-column.php#116214
     */
    public static function arrayColumn($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();
        if ($argc < 2) {
            trigger_error("ArrayRecursive::arrayColumn() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }
        if (!is_array($params[ 0 ])) {
            // Because we call back to this function, check if call was made by self to
            // prevent debug/error output for recursiveness :)
            $callers = debug_backtrace();
            if ($callers[ 1 ][ 'function' ] != 'arrayColumn') {
                trigger_error(
                    'ArrayRecursive::arrayColumn() expects parameter 1 to be array, ' . gettype(
                        $params[ 0 ]
                    ) . ' given',
                    E_USER_WARNING
                );
            }
            return null;
        }
        if (!is_int($params[ 1 ])
            && !is_float($params[ 1 ])
            && !is_string($params[ 1 ])
            && $params[ 1 ] !== null
            && !(is_object($params[ 1 ]) && method_exists($params[ 1 ], '__toString'))
        ) {
            trigger_error(
                'ArrayRecursive::arrayColumn(): The column key should be either a string or an integer',
                E_USER_WARNING
            );
            return false;
        }
        if (isset($params[ 2 ])
            && !is_int($params[ 2 ])
            && !is_float($params[ 2 ])
            && !is_string($params[ 2 ])
            && !(is_object($params[ 2 ]) && method_exists($params[ 2 ], '__toString'))
        ) {
            trigger_error(
                'ArrayRecursive::arrayColumn(): The index key should be either a string or an integer',
                E_USER_WARNING
            );
            return false;
        }
        $paramsInput = $params[ 0 ];
        $paramsColumnKey = ($params[ 1 ] !== null) ? (string)$params[ 1 ] : null;
        $paramsIndexKey = null;
        if (isset($params[ 2 ])) {
            if (is_float($params[ 2 ]) || is_int($params[ 2 ])) {
                $paramsIndexKey = (int)$params[ 2 ];
            } else {
                $paramsIndexKey = (string)$params[ 2 ];
            }
        }
        $resultArray = array();
        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;
            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string)$row[ $paramsIndexKey ];
            }
            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[ $paramsColumnKey ];
            }

            $possibleValue = self::arrayColumn($row, $paramsColumnKey, $paramsIndexKey);
            if ($possibleValue) {
                $resultArray = array_merge($possibleValue, $resultArray);
            }

            if ($valueSet) {
                if ($keySet) {
                    $resultArray[ $key ] = $value;
                } else {
                    $resultArray[ ] = $value;
                }
            }
        }

        return $resultArray;
    }
}
