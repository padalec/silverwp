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
 Repository path: $HeadURL: $
 Last committed: $Revision: $
 Last changed by: $Author: $
 Last changed date: $Date: $
 ID: $Id: $
*/
namespace SilverWp\ThemeOption\Menu;

use SilverWp\Helper\Control\Select;
use SilverWp\Helper\Control\Slider;
use SilverWp\Helper\Control\Text;
use SilverWp\Helper\Control\Toggle;
use SilverWp\Helper\Control\Upload;
use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\ThemeOption\Menu\ContactPage' ) ) {

    /**
     *
     * Contact page theme options
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage ThemeOption\Menu
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class ContactPage extends MenuAbstract {

        protected function createMenu() {
            $this->setName( 'contact_page' );
            $this->setIcon( 'font-awesome:fa-css3' );
            $this->setTitle( Translate::translate( 'Contact page' ) );

            $section = new Section( 'google_map' );
            $section->setLabel( Translate::translate( 'Google Map' ) );

            $toggle = new Toggle( 'use_google_map' );
            $toggle->setLabel( Translate::translate( 'Map enable' ) . ':' );
            $section->addControl( $toggle );

            $map_type = new Select( 'google_map_type' );
            $map_type->setLabel( Translate::translate( 'Map type' ) . ':' );
            $map_type->setOptions( silverwp_get_map_type() );
            $map_type->setDefault( 'roadmap' );
            $map_type->setDependency( $toggle, 'vp_dep_boolean' );
            $section->addControl( $map_type );

            $point_latitude = new Text( 'google_map_point_latitude' );
            $point_latitude->setLabel( Translate::translate( 'Point coordinates latitude' ) . ':' );
            $point_latitude->setDependency( $toggle, 'vp_dep_boolean' );
            $point_latitude->setValidation( 'number' );
            $point_latitude->setDescription(
                Translate::translate(
                    'You can define coordinates using this <a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">tool</a>.'
                )
            );
            $section->addControl( $point_latitude );

            $point_longitude = new Text( 'google_map_point_longitude' );
            $point_longitude->setLabel( Translate::translate( 'Point coordinates longitude' ) . ':' );
            $point_longitude->setDependency( $toggle, 'vp_dep_boolean' );
            $point_longitude->setValidation( 'number' );
            $point_longitude->setDescription(
                Translate::translate(
                    'You can define coordinates using this <a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">tool</a>.'
                )
            );
            $section->addControl( $point_longitude );

            $map_zoom = new Slider( 'google_map_zoom' );
            $map_zoom->setLabel( Translate::translate( 'Zoom' ) . ':' );
            $map_zoom->setDependency( $toggle, 'vp_dep_boolean' );
            $map_zoom->setDefault( 0 );
            $map_zoom->setMin(0);
            $map_zoom->setMax(19);
            $map_zoom->setStep(1);
            $section->addControl( $map_zoom );

            $map_icon = new Upload( 'google_map_icon' );
            $map_icon->setLabel( Translate::translate( 'Custom Marker Icon' ) . ':' );
            $map_icon->setDependency( $toggle, 'vp_dep_boolean' );
            $section->addControl( $map_icon );

            $this->addControl( $section );
        }
    }
}