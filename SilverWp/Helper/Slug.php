<?php
namespace SilverWp\Helper;
use SilverWp\Helper\Iconv;
/**
 * 
 * czyszczenie stringow ze znakow dialektycznych
 * 
 * @author Włodzimierz Gajda http://zend-framework.gajdaw.pl/
 * @category SilverCms
 * @package Helper
 * @version 1.0
 * @link http://zend-framework.gajdaw.pl/listingi-txt/listing-21-02.txt
 */
class Slug {
    /**
     * wyczyszczenie polskich znakow diakrytycznych, 
     * usuniecie znakow roznych o liter i cyfr zamiana ich na separator 
     * zmiana z duzych liter na male
     * @see string2slug()
     * @param array $options = array(
     *                'encoding'  => 'utf-8',
     *                'default'   => 'undefined',
     *                'separator' => '-',
     *                'maxlength' => 100,
     *                'case'      => 'lower'
     *   );
     * @return string
     * @static
     */
    public static function string2slug( $string, $options = array())
    {

        if ( ! isset( $options['separator'] ) ) {
            $options['separator'] = '_';
        }

        if ( ! isset( $options['default'] ) ) {
            $options['default'] = 'undefined';
        }

        if ( ! isset( $options['encoding'] ) ) {
            $options['encoding'] = 'utf-8';
        }

        if ( ! isset( $options['case'] ) ) {
            $options['case'] = 'lower';
        }

        switch ( $options['encoding'] ) {
            case 'utf-8':
                $string = Iconv::utf82ascii( $string );
                break;
            case 'iso-8859-2':
                $string = Iconv::iso2ascii( $string );
                break;

            case 'windows-1250':
                $string = Iconv::win2ascii( $string );
                break;
        }
        
        $string = preg_replace('/[^A-Za-z0-9]/', $options['separator'], $string);

        if ( isset( $options['case'] ) ) {
            if ( $options['case'] == 'lower' ) {
                $string = strtolower( $string );
            } else if ( $options['case'] == 'upper' ) {
                $string = strtoupper( $string );
            }
        }

        //$string = preg_replace( '/' . preg_quote( $options['separator'], '/' ) . '{2,}/', $options['separator'], $string );
        
        $string = trim( $string, $options['separator'] );

        if ( isset( $options['maxlength'] ) ) {
            $string = self::short2lenght( $string, $options );
        }

        if ($string === '') {
            return $options['default'];
        } else {
            return $string;
        }
    }
    /**
     * usuniecie znacznikow htmlowych oraz konwersja encji
     * @param string $string
     * @param array $options {@link string2slug()} 
     * @return string 
     */
    public static function html2slug($string, $options = array())
    {
        if (!isset($options['encoding'])) {
            $options['encoding'] = 'utf-8';
        }
        $string = strip_tags($string);
        $string = html_entity_decode($string, ENT_QUOTES, $options['encoding']);
        $string = self::string2slug($string, $options);
        return $string;
    }
    /**
     * skrocenie napisu do podanej dlugosci
     * @param string $string
     * @param array $options = array('maxlength' => 100,'separator'=>'_')
     * @return string 
     */

    public static function short2lenght($string, $options = array())
    {
        if (isset($options['maxlength'])) {
            $maxlength = $options['maxlength'];
        } else {
            $maxlength = 100;
        }

        if (strlen($string) < $maxlength) {
            return $string;
        }

        if (isset($options['separator'])) {
            $separator = $options['separator'];
        } else {
            $separator = '_';
        }

        $i = $maxlength;

        do {
            $i--;
        } while (($i >= 0) && ($string[$i] != $separator));

        return substr($string, 0, $i);
    }
    /**
     * 
     * remove puncation and all special characters from string
     * 
     * @param string $string string to cleared
     * @param array $options array with options defualt array( 'separator' => '' )
     * @return string cleared string
     */
    public static function clear_string( $string, $options = array( 'separator' => '_' ) ){
        $string = preg_replace('/[^A-Za-z0-9\_]/', $options['separator'], $string );
        return strtolower( $string );
    }
}
