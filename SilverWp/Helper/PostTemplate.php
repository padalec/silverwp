<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/PostTemplate.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: PostTemplate.php 2184 2015-01-21 12:20:08Z padalec $
 */

namespace SilverWp\Helper;

/**
 * Helpers fuctions for PostTemplates
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: PostTemplate.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */

class PostTemplate
{
    /**
     *
     * add custom css classes to body tag
     *
     * @param array $classes
     * @return array
     * @static
     * @link https://codex.wordpress.org/Function_Reference/body_class#Add_Classes_By_Filters example
     */
    public static function body_class($classes)
    {
        if ( is_page_template('contact-page.php') && \SilverWp\Helper\Option::get_theme_option( 'use_google_maps' ) === '1' ) {
            $classes[] = 'google-map';
        }
        return $classes;
    }
}
