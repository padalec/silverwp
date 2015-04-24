<?php

/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/User.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: User.php 2184 2015-01-21 12:20:08Z padalec $
 */

namespace SilverWp\Helper;

/**
 * Helper for Users
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: User.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
class User extends \WP_User {
    /**
     * 
     * get current user roles
     * 
     * @return array
     * @static
     * @access public
     */
    public function get_user_role(){
        return array_shift( $this->roles );
    }
}
