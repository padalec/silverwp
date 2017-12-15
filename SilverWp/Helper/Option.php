<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Option.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: Option.php 2184 2015-01-21 12:20:08Z padalec $
 */

/**
 * Options Helpers
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: Option.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage Helper
 */
namespace SilverWp\Helper;

class Option {
    /**
     * get option name with prefix
     * 
     * @param string $name - option name 
     * @return string
     */
    public static function get_name( $name ) {
        return strtolower( '_' . THEME_OPTION_PREFIX . '_' . $name );
    }
    /**
     * 
     * check if the option exist if not add
     * 
     * @param array $option
     * @param int $index
     * @param mixed $value
     * @return array
     */
    public static function set_default_option( &$option, $index, $value )
    {
        if( ! array_key_exists( $index, (array)$option ) ) {
            return;
        }
        $option[$index] = $value;
    }
    
    /**
     * 
     * update theme options
     * 
     * @link http://codex.wordpress.org/Function_Reference/update_option alias to update_option 
     * @param string $name
     * @param mixed $option string or array
     * @return boolean
     */
    public static function update_theme_option( $name, $option ){
        $name = self::get_name( $name );
        return update_option( $name, $option );
    }
    /**
     * 
     * update core or additional options
     * 
     * @param string $name
     * @param mixed $value
     * @return boolean
     */
    public static function update_option( $name, $value ){
        return update_option( $name, $value );
    }

    /**
     * 
     * add theme options
     * 
     * @link https://codex.wordpress.org/Function_Reference/add_option
     * @param string $name
     * @param mixed $option string or array
     * @return boolean False if option was not added and true if option was added
     * @static
     */
    public static function add_theme_option( $name, $option, $autoload = true ){
        $name = self::get_name( $name );
        return add_option( $name, $option, '', $autoload );
    }
    /**
     * 
     * add core and additional options 
     * 
     * @param string $name
     * @param mixed $value string or array
     * @param boolean $autoload
     * @return boolean False if option was not added and true if option was added
     */
    public static function add_option( $name, $value, $autoload = true ){
        return add_option( $name, $value, '', $autoload );
    }
    /**
     * 
     * get theme option value
     * 
     * @param string $name - option name
     * @param boolean $to_array - convert string keys to array
     * @return mixed Current value for the specified option. If the specified 
     *               option does not exist, returns boolean FALSE.
     * @static
     */
    public static function get_theme_option( $name, $to_array = false ) {
        $options = get_option( THEME_OPTION_PREFIX );
        if ( $to_array ) {
            $options = UtlArray::string_to_array( $options );
        }
        return isset( $options[ $name ] ) ? $options[ $name ] : false;
    }
    /**
     * 
     * get core and additional options
     * 
     * @param string $name
     * @return mixed
     */
    public static function get_option( $name ){
        return get_option( $name );
    }
    /**
     * 
     * sorting array by order field
     * 
     * @param array $array_in array to sort
     * @param array $fields list of fields 
     * @param string $order_field order theme option field name
     * @return array array sorted by order column
     * @todo if statemend maby should by get from function param?  
     */
    public static function option_sort_by_order( $array_in, $fields = array(), $order_field = 'order' ){
        $array_out = array(); 
        foreach ( $array_in as $name => $label ){
            $name = StringOperation::sanitize_text_field( $name );
            foreach( $fields as $key => $field ){
                if ( '' != ( $order = self::get_theme_option( $order_field . '[' . $name . ']' ) ) ){
                    
                    $field_value = self::get_theme_option( $field.'[' . $name . ']' );
                    
                    $array_out[] = array( 
                        'slug'  => $name,
                        'label' => $label,
                        'order' => $order,
                        $key    => $field_value,
                    );
                }
            }
        }
        UtlArray::array_sort_by_column( $array_out, 'order' );
        return $array_out;
    }
}

