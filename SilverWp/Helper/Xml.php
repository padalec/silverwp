<?php

/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Xml.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: Xml.php 2184 2015-01-21 12:20:08Z padalec $
 */

namespace SilverWp\Helper;

/**
 * Xml functions 
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: Xml.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
class Xml {
    
    /**
     * Takes XML string and returns a boolean result where valid XML returns true
     * 
     * @return boolean if it's a valid xml return true
     * @static
     * @access public
     */
    public static function is_valid_xml( $xml ) {
        if (!empty($xml)) {
            libxml_use_internal_errors( true );

            $doc = new \DOMDocument( '1.0', 'utf-8' );

            $doc->loadXML( $xml );

            $errors = libxml_get_errors();

            return empty( $errors );
        } else {
            return false;
        }
    }
}
