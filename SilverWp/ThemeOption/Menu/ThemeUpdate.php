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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ThemeOption/Menu/ThemeUpdate.php $
  Last committed: $Revision: 2575 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-03-16 10:52:36 +0100 (Pn, 16 mar 2015) $
  ID: $Id: ThemeUpdate.php 2575 2015-03-16 09:52:36Z padalec $
 */
namespace SilverWp\ThemeOption\Menu;

use SilverWp\Helper\Control\Notebox;
use SilverWp\Helper\Control\Text;
use SilverWp\Translate;

if ( ! class_exists( '\SilverWp\ThemeOption\Menu\ThemeUpdate' ) ) {

    /**
     * Theme update submenu page
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage ThemeOption\Menu
     * @author Michal Kalkowski <michal at dynamite-studio.pl>
     * @copyright Dynamite-Studio.pl 2014
     * @version $Id: ThemeUpdate.php 2575 2015-03-16 09:52:36Z padalec $
     */
    class ThemeUpdate extends MenuAbstract {

        /**
         *
         * Create menu
         *
         * @access protected
         * @return void
         */
        protected function createMenu() {
            $this->setName( 'theme_update' );
            $this->setLabel( Translate::translate( 'Theme update' ) );
            $this->setIcon( 'font-awesome:fa-home' );

            $section  = new Section( 'theme_api' );
            $note_box = new Notebox( 'note_box' );
            $note_box->setLabel( Translate::translate( 'Update your Theme from the WordPress Dashboard' ) );
            $note_box->setDescription( Translate::translate( 'If you want to get update notifications for your themes and if you want to be able to update your theme from your WordPress backend you need to enter your Themeforest account name as well as your Themeforest Secret API Key below:' ) );
            $section->addControl( $note_box );

            $user_name = new Text( 'tf_user_name' );
            $user_name->setLabel( Translate::translate( 'Your Themeforest User Name' ) );
            $user_name->setDescription( Translate::translate( 'Enter the Name of the User you used to purchase this theme' ) );
            $section->addControl( $user_name );

            $api_key = new Text( 'tf_api_key' );
            $api_key->setLabel( Translate::translate( 'Your Themeforest API Key' ) );
            $api_key->setDescription( Translate::translate( 'Enter the API Key of your Account here. <a href="">You can find your API Key here</a>' ) );
            $section->addControl( $api_key );

            $this->addControl( $section );
        }
    }
}
