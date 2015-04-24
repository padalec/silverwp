<?php
/*
 * Copyright (C) 2014 Michal Kalkowski <michal at silversite.pl>
 *
 * SilverWp is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * SilverWp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
/*
 Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Message.php $
 Last committed: $Revision: 2446 $
 Last changed by: $Author: padalec $
 Last changed date: $Date: 2015-02-13 13:30:38 +0100 (Pt, 13 lut 2015) $
 ID: $Id: Message.php 2446 2015-02-13 12:30:38Z padalec $
*/
namespace SilverWp\Helper;

/**
 * Display messages
 *
 * @category WordPress
 * @package SilverWp
 * @subpackage Helper
 * @author Michal Kalkowski <michal at dynamite-studio.pl>
 * @copyright Dynamite-Studio.pl 2014
 * @version $Id: Message.php 2446 2015-02-13 12:30:38Z padalec $
 */
if ( ! class_exists( 'Message' ) ) {
    class Message {

        /**
         * Display message
         *
         * @param string      $message message text
         * @param string      $type message type. Available types: update, error, update-nag
         * @param null|string $screen page name when message will be displayed
         *
         * @return string
         * @static
         * @access public
         * @link http://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices
         */
        public static function display( $message, $type = 'info', $screen = null ) {
            global $current_screen;
            $content = '';
            if ( is_null( $screen ) || $current_screen->base == $screen ) {
                $content .= '<div id="message" class="' . $type . '">';
                    $content .= '<strong>' . $message . '</strong>';
                $content .= '</div>';
            }

            return $content;
        }

        /**
         *
         * Display alert
         *
         * @param string $message alert text
         * @param string $type css class name for alert type
         *
         * @return string
         * @static
         * @access public
         */
        public static function alert( $message, $type= 'alert-warning' ) {
            $content = '';
            $content .= '<div class="alert ' . $type . '">';
            $content .= $message;
            $content .= '</div>';
            return $content;
        }
    }
} 