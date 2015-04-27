<?php

/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/libs/ssvafpress/classes/control/field/regularselect.php $
  Last committed: $Revision: 1572 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2014-10-02 13:22:19 +0200 (Cz, 02 paź 2014) $
  ID: $Id: regularselect.php 1572 2014-10-02 11:22:19Z padalec $
 */

/**
 * Control select field withowt js script
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: regularselect.php 1572 2014-10-02 11:22:19Z padalec $
 * @category Classes
 * @package Control
 * @subpackage Filed
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */

class VP_Control_Field_regularselect extends VP_Control_Field_Select {
    public function render( $is_compact = true ) {
        $is_compact = true;
        parent::render( $is_compact );
    }
}

