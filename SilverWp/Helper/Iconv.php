<?php
namespace SilverWp\Helper;
/**
 * SilverCms_Core_Format_Iconv
 * konwerter znakow dialektycznych
 * @author http://zend-framework.gajdaw.pl/listingi-txt/listing-21-01.txt
 * @category SilverCms
 * @package Core
 * @subpackage Format
 * @version 1.0
 * @link http://zend-framework.gajdaw.pl/listingi-txt/listing-21-01.txt
 */

class Iconv {

    const ASCII_ALL = 'acelnoszzACELNOSZZ';

    //ąćęłńóśźżĄĆĘŁŃÓŚŹŻ
    const ISO_ALL = "\xb1\xe6\xea\xb3\xf1\xf3\...";

    //ąśźĄŚŹ
    const ISO_SPECIFIC = "\xb1\xb6\xbc\xa1\xa6\xac";

    //ąśźĄŚŹ
    const WIN_SPECIFIC = "\xb9\x9c\x9f\xa5\x8c\x8f";

    public static $_ARRAY_TRANSLITERATE = array(
            'é' => 'e', 'ö' => 'o', 'ş' => 's', 'ü' => 'u',
            'á' => 'a', 'ñ' => 'n', 'ç' => 'c', 'è' => 'e',
            'ß' => 'ss'
        );


    /**
     * zmiana kodowania z iso na windows 
     * @param string $string - Source: ISO-8859-2
     * @return string
     * @static
     */
    public static function iso2win($string)
    {
        return strtr($string, self::ISO_SPECIFIC, self::WIN_SPECIFIC);
    }
    /**
     * zmiana kodowania z ISO-8859-2 na UTF-8
     * @param string $string - Source: ISO-8859-2
     * @return string 
     */
    public static function iso2utf8($string)
    {
        return iconv('ISO-8859-2', 'UTF-8', $string);
    }
    /**
     * zmiana znakow z ISO na ASCII
     * @param string $string Source: ISO-8859-2
     * @return type 
     */
    public static function iso2ascii($string)
    {
        return strtr($string, self::ISO_ALL, self::ASCII_ALL);
    }



    /**
     * zmiana kodowania z UTF8 na ISO-8859-2
     * @param string $string - Source: UTF-8
     * @return string
     * @static
     */
    public static function utf82iso($string)
    {
        return iconv('UTF-8', 'ISO-8859-2', $string);
    }
    /**
     * zmiana kodowania z UTF8 na WINDOWS-1250
     * @param string $string - Source: UTF-8
     * @return string
     * @static
     */
    
    public static function utf82win($string)
    {
        return iconv('UTF-8', 'WINDOWS-1250', $string);
    }
    /**
     * zmiana kodowania z UTF8 na ASCII
     * @param string $string - Source: UTF-8
     * @return string
     * @static
     */
    
    public static function utf82ascii($string)
    {
        $string = self::transliterate($string);

        /*
         * Urywamy wszystkie ogonki różne od polskich
         * Polskie ogonki kodujemy w iso
         */
        $string = iconv('utf-8', 'ISO-8859-2//TRANSLIT//IGNORE', $string);

        /*
         * urywamy polskie ogonki
         */
        $string = self::iso2ascii($string);

        return $string;
    }
    /**
     * zmiana danej litery ogonkowej 
     * na odpowiadajaca litere bez ogonkowa
     * @param string $string
     * @return string 
     */
    public static function transliterate($string)
    {
        return str_replace(
            array_keys(self::$_ARRAY_TRANSLITERATE),
            array_values(self::$_ARRAY_TRANSLITERATE),
            $string
        );
    }

}
