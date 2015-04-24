<?php

/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Ajax/AjaxInterface.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: AjaxInterface.php 2184 2015-01-21 12:20:08Z padalec $
 */

namespace SilverWp\Ajax;


/**
 * Ajax Interface
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: AjaxInterface.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package
 * @subpackage
 * @copyright @copyright (c) 2009 - 2014, SilverSite.pl
 */

interface AjaxInterface
{
    public function scriptsRegister();
    /**
     * ajax response
     *
     * @abstract
     * @access public
     */

    public function ajaxResponse();
    public function scriptsLocalize();
}
