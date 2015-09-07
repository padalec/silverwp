<?php
/*
 * Copyright (C) 2014 Michal Kalkowski <michal at silversite.pl>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Exception.php $
  Last committed: $Revision: 2182 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:00:49 +0100 (Śr, 21 sty 2015) $
  ID: $Id: Exception.php 2182 2015-01-21 12:00:49Z padalec $
 */
namespace SilverWp;
use SilverWp\Helper\Message;

/**
 * Exception handler
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: Exception.php 2182 2015-01-21 12:00:49Z padalec $
 * @category WordPress
 * @package SilverWp
 */

class Exception extends \Exception {
    /**
     *
     * @see Exception
     *
     * @param string $message
     * @param int    $code
     * @param null   $previous
     */
    public function __construct( $message, $code = 0, $previous = null ) {
        parent::__construct( $message, $code, $previous );
        add_action( 'admin_notices', array( $this, 'displayAdminNotice' ) );
    }

    /**
     * display message in admin screen
     *
     * @global mixed $current_screen
     * @access public
     * @return string
     */
    public function displayAdminNotice() {
        global $current_screen;
        $content = '';
        $content .= '<div id="message" class="error">';
        $content .= '<strong>' . $this->getMessage() . '</strong>';
        if ( WP_DEBUG ) {
            $content .= '<pre>Stack Trace:' . $this->getTraceAsString() . '</pre>';
            $content .= '<pre>Full Stack Trace:' . print_r( $this->getTrace(), true ) . '</pre>';
        }
        $content .= '</div>';


        return $content;
    }

    /**
     * Catch exception and display error message
     *
     * @access public
     */
    public function catchException() {
        if ( is_admin() ) {
            echo $this->displayAdminNotice();
        } else {
	        echo Message::alert( $this->getMessage(), 'alert-error' );
            if ( WP_DEBUG ) {
                \SilverWp\Debug::dumpPrint( $this->getTrace() );
            }
        }
    }
}
