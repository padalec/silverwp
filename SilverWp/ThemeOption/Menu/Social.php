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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ThemeOption/Menu/Social.php $
  Last committed: $Revision: 2569 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-03-13 17:46:33 +0100 (Pt, 13 mar 2015) $
  ID: $Id: Social.php 2569 2015-03-13 16:46:33Z padalec $
 */

namespace SilverWp\ThemeOption\Menu;

use SilverWp\Helper\Control\Text;
use SilverWp\Helper\Control\Toggle;
use SilverWp\Translate;

if ( ! class_exists( '\SilverWp\ThemeOption\Menu\Social' ) ) {
    /**
     * Social Providers Theme Options
     *
     * @author Michal Kalkowski <michal at silversite.pl>
     * @version $Id: Social.php 2569 2015-03-13 16:46:33Z padalec $
     * @category WordPress
     * @package SilverWp
     * @subpackage ThemeOption\Menu
     * @copyright (c) 2009 - 2014, SilverSite.pl
     */
    class Social extends MenuAbstract {
        public function createMenu() {
            $this->setName( 'social' );
            $this->setLabel( Translate::translate( 'Social' ) );
            $this->setIcon( 'font-awesome:fa-social' );

            $section = new Section( 'share' );
            $section->setTitle( Translate::translate( 'Share providers' ) );

            $providers = silverwp_get_social_providers();
            foreach ( $providers as $provider ) {
                if ( $provider[ 'share_url' ] != '' ) {
                    $toggle = new Toggle( 'social_share_providers[' . sanitize_title( $provider[ 'name' ] ) . ']' );
                    $toggle->setLabel( ucfirst( $provider[ 'name' ] ) );
                    $section->addControl( $toggle );
                }
            }

            $this->addControl( $section );

            $section = new Section( 'social_accounts' );
            $section->setTitle( Translate::translate( 'Social accounts' ) );

            $accounts = silverwp_get_social_accounts();
            foreach ( $accounts as $slug => $name ) {
                $url = new Text( 'social_accounts[' . $slug . '][url]' );
                $url->setLabel( Translate::params( '%s URL', ucfirst( $name ) ) . ':' );
                $url->setValidation( 'url' );
                $section->addControl( $url );

                $order = new Text( 'social_accounts[' . $slug . '][order]' );
                $order->setLabel( Translate::params( '%s order', ucfirst( $name ) ) . ':' );
                $order->setDefault( 0 );
                $section->addControl( $order );
            }

            $this->addControl( $section );
        }
    }
}