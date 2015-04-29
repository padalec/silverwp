<?php
/*
 * Copyright (C) 2014 Michal Kalkowski <michal at dynamite-studio.pl>
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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/AutoUpdate.php $
  Last committed: $Revision: 2269 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-29 12:51:45 +0100 (Cz, 29 sty 2015) $
  ID: $Id: AutoUpdate.php 2269 2015-01-29 11:51:45Z padalec $
 */
namespace SilverWp;

use SilverWp\Helper\Theme;
use SilverWp\Helper\Option;
use SilverWp\Helper\Message;

if ( ! class_exists( '\SilverWp\AutoUpdate' ) ) {

    /**
     * Integrate with evanto API and auto update theme
     *
     * @category WordPress
     * @package SilverWp
     * @author Michal Kalkowski <michal at dynamite-studio.pl>
     * @copyright Dynamite-Studio.pl 2014
     * @version $Revision: 2269 $
     */

    class AutoUpdate extends SingletonAbstract {
        /**
         * @var string
         * @access private
         */
        private $author;
        /**
         * @var string
         * @access private
         */
        private $theme_name;
        /**
         * @var string
         * @access private
         */
        private $api_key;
        /**
         * @var string
         * @access private
         */
        private $user_name;
        /**
         * @var string
         * @access private
         */
        private $version;

        /**
         * Class constructor initialize class
         *
         * @access protected
         * @todo implements re_insert_custom_css
         */
        protected function __construct() {
            $this->user_name  = Option::get_theme_option( 'tf_user_name' );
            $this->api_key    = Option::get_theme_option( 'tf_api_key' );
            $this->author     = Theme::getThemeInfo( 'Author' );
            $this->theme_name = Theme::getThemeInfo( 'Name' );
            $this->version    = Theme::getThemeInfo( 'Version' );

            add_action( 'admin_notices', array( $this, 'backendHtml' ) );
            add_action( 'update_bulk_theme_complete_actions', array( $this, 'updateComplete' ), 10, 2 );
            //add_action( 'upgrader_process_complete', array( $this, 're_insert_custom_css' ) );
            //add_action( 'load-update.php', array( $this, 'temp_save_custom_css' ), 20 );
            $this->includes();
        }

        /**
         * Include external class
         *
         * @access private
         * @return void
         */
        private function includes() {
            if ( ! empty( $this->user_name ) && ! empty( $this->api_key ) ) {
                \PixelentityThemeUpdate::init( $this->user_name, $this->api_key, $this->author );
            }
        }

        /**
         * Check theme update is required
         *
         * @return bool
         * @access public
         */
        public function isThemeUpdated() {
            $updates = get_site_transient( 'update_themes' );
            if ( ! empty( $updates ) && ! empty( $updates->response ) ) {
                $theme = wp_get_theme();
                if ( ( $key = array_key_exists( $theme->get_template(), $updates->response ) ) ) {
                    $update_response = $updates->response[ $theme->get_template() ];

                    return $update_response;
                }
            }

            return false;
        }

        /**
         * After theme update display info message and back to options
         *
         * @param $updates
         * @param $info
         *
         * @return array
         * @access
         */
        public function updateComplete( $updates, $info ) {
            if ( strtolower( $info->get( 'Name' ) ) == strtolower( $this->theme_name ) ) {
                $label   = Translate::params( 'Go Back to %s Theme Panel', $this->theme_name );
                $updates = array(
                    'theme_updates' => '<a target="_parent" href="' . admin_url( 'themes.php?page=silverwp-theme_options' ) . '">' . $label . '</a>'
                );
            }

            return $updates;
        }

        /**
         * Display update information
         *
         * @access public
         */
        public function backendHtml() {
            global $current_screen;
            $parent_string = is_child_theme() ? 'Parent Theme (' . ucfirst( $this->theme_name ) . ')' : 'Theme';

            if ( empty( $this->user_name ) || empty( $this->api_key ) ) {

                echo Message::display(
                    Translate::params(
                        'Once you have entered and saved your Username and API Key WordPress will check for
                            updates every 12 Hours and notify you here, if one is available <br/><br/> Your
                            current %s Version Number is <strong> %s </strong>',
                        $parent_string,
                        $this->version
                    ),
                    'updated'
                );
                UpdateNotifier::getInstance();
            } else if ( ( $update = $this->isThemeUpdated() ) !== false ) {
                if ( $current_screen->base != 'update-core' ) {

                    $target = network_admin_url( 'update-core.php?action=do-theme-upgrade' );
                    $data   = array(
                        'new_version' => $update[ 'new_version' ],
                        'target'      => $target,
                        'parent'      => $parent_string,
                        'version'     => $this->version,
                        'theme_name'  => $this->theme_name
                    );

                    $view = View::render( 'Helper/auto-update', $data );
                    echo $view;
                }
            }
        }
    }
}
