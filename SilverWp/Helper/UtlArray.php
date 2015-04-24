<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/UtlArray.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: UtlArray.php 2184 2015-01-21 12:20:08Z padalec $
 */

/**
 * Array utility helper
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: UtlArray.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */

namespace SilverWp\Helper;

class UtlArray {
    /**
     * 
     * convert object to array
     * 
     * @param mixed $data - array or object
     * @return array
     */
    public static function object_to_array( $data ) {
        if ( is_array( $data ) || is_object( $data ) ) {
            $result = array();
            foreach ( $data as $key => $value ){
                $result[$key] = self::object_to_array( $value );
            }
            return $result;
        }
        return $data;
    }
    /**
     * remove some kays from array
     * @param array $main_array
     * @param array $remove_part - keys should be removed from array
     * @return array
     */
    public static function array_remove_part( $main_array, $remove_part ) {
        return array_diff_key( $main_array, array_flip( $remove_part ) );
    }
    
    /**
     * 
     * sort array by kay
     * 
     * @param array $array - array for sort
     * @param string $key_name - kay name used for sort
     * @param int $sort_type - sort type
     */
    public static function array_sort_by_column( &$array, $key_name, $sort_type = SORT_ASC ) {
        $return = array();
        foreach( $array as $key => $row ) {
            $return[ $key ] = $row[ $key_name ];
        }
        array_multisort( $return, $sort_type, $array );
        return $return;
    }
    /**
     * 
     * remove ale null or empty value from array
     * 
     * @param array $array_in array to move empty value
     * @return array array without emoty value
     * @todo add recursive 
     */
    public static function array_remove_empty( array $array_in ){
        $array_out = Array();
        foreach( $array_in as $key => $value){
            if( is_scalar( $value ) ){
                if( !empty( $value ) && $value != '' && !is_null( $value ) ){
                    $array_out[ $key ] = $value; 
                }
            }
        }
        return $array_out;
    }
    /**
     * convert stirng like sample[name] to array
     * 
     * @param array $array_in
     * @return array
     */
    public static function string_to_array(array $array_in)
    {
        $result = array();
        parse_str(http_build_query($array_in), $result);
        return $result;
    }
        
    public static function optimize(array &$array_in)
    {
        $array_out = array();
        if (\is_array($array_in)) {
            if (isset($array_in[0]) && \count($array_in) == 1) {
                $array_op = $array_in[0];
                if (\is_array($array_op)) {
                    foreach ($array_op as &$sub) {
                        if (\is_array($sub)) {
                            $array_out[] = self::optimize($sub);
                        }
                    }
                }
            } else {
                foreach ($array_in as &$sub) {
                    if (\is_array($sub)) {
                        $array_out[] = self::optimize($sub);
                    }
                }
            }
            return $array_out;
        }
    }
}
