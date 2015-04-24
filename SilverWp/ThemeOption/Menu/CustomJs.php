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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ThemeOption/Menu/CustomJs.php $
  Last committed: $Revision: 2569 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-03-13 17:46:33 +0100 (Pt, 13 mar 2015) $
  ID: $Id: CustomJs.php 2569 2015-03-13 16:46:33Z padalec $
 */

namespace SilverWp\ThemeOption\Menu;

use SilverWp\Helper\Control\CodeEditor;
use SilverWp\Helper\Control\Text;
use SilverWp\Helper\Control\Toggle;
use SilverWp\Translate;

if ( ! class_exists( '\SilverWp\ThemeOption\Menu\CustomJs' ) ) {
    /**
     * CustomJs Providers Theme Options
     *
     * @author Michal Kalkowski <michal at silversite.pl>
     * @version $Id: CustomJs.php 2569 2015-03-13 16:46:33Z padalec $
     * @category WordPress
     * @package SilverWp
     * @subpackage ThemeOption\Menu
     * @copyright (c) 2009 - 2014, SilverSite.pl
     */
    class CustomJs extends MenuAbstract {
        public function createMenu() {
            $this->setName( 'tracking_js' );
            $this->setLabel( Translate::translate( 'Tracking & Javascript' ) );
            $this->setIcon( 'font-awesome:fa-js' );

            $section = new Section( 'js' );
            $section->setTitle( Translate::translate( 'Javascript' ) );
            $header_js = new CodeEditor( 'js_header_code' );
            $header_js->setLabel( Translate::translate( 'Custom code in Head page' ) );
            $header_js->setDescription( Translate::translate( 'Any code you place here will appear before &lt;/head&gt; tag of every.' ) );
            $header_js->setTheme( 'github' );
            $header_js->setMode( 'javascript' );
            $section->addControl( $header_js );

            $body_js = new CodeEditor( 'js_body_code' );
            $body_js->setLabel( Translate::translate( 'Custom code in Body page' ) );
            $body_js->setDescription( Translate::translate( 'Any code you place here will appear before &lt;/body&gt; tag of every page of your site.' ) );
            $body_js->setTheme( 'github' );
            $body_js->setMode( 'javascript' );
            $section->addControl( $body_js );

            $this->addControl( $section );

            $section = new Section( 'tracking' );
            $section->setTitle( Translate::translate( 'Tracking' ) );
            $tracking_code = new CodeEditor( 'tracking_code' );
            $tracking_code->setLabel( Translate::translate( 'Google analytics tracking code' ) );
            $tracking_code->setTheme( 'github' );
            $tracking_code->setMode( 'javascript' );
            $section->addControl( $tracking_code );

            $this->addControl( $section );
        }
    }
}