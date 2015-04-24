<?php

/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Filter.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: Filter.php 2184 2015-01-21 12:20:08Z padalec $
 */

namespace SilverWp\Helper;

/**
 * Super global array Filters
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: Filter.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage Helper
 */
class Filter
{

    public static function get_var($name, $method = FILTER_DEFAULT, $default = NULL)
    {
        return filter_input(INPUT_GET, $name, $method, array('options' => array('defualt' => $default)));
    }

    public static function post_var($name, $method = FILTER_DEFAULT, $default = NULL)
    {
        return filter_input(INPUT_POST, $name, $method, array('options' => array('defualt' => $default)));
    }

    /**
     * get filtered data from super global varibale $_SERVER
     * 
     * @param string $name server varibale name
     * @return mixed
     * @access public
     * @static
     */
    public static function server($name)
    {
        $var = \filter_input(INPUT_SERVER, $name);
        return $var;
    }

    /**
     * Filter variable
     * 
     * @param mixed $var variable to filter
     * @param int $method filter method default: FILTER_DEFAULT
     * @param mixed $default default value if value doesn't exists
     * @param array $options additional filter options
     * 
     * @static
     * @return mixed filtered variable
     */
    public static function variable($var, $method = FILTER_DEFAULT, $default = null, $options = array())
    {
        $default_options = array(
            'options' => array(
                'defualt' => $default,
            )
        );

        $options = array_replace($default_options, $options);
        $variable = filter_var($var, $method, $options);
        return $variable;
    }

    /**
     * 
     * check and retur remote ip address
     * 
     * @access public
     * @static
     * @return boolean|string if ip addres is not valid return boolean false else return ip address
     */
    public static function ip($ip = null)
    {
        //$ip = filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_FLAG_IPV4 );
        $ip = \is_null($ip) ? self::server('REMOTE_ADDR') : $ip;
        if (\filter_var($ip, \FILTER_VALIDATE_IP)) {
            return $ip;
        }
        return false;
    }

}
