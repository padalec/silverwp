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
namespace SilverWp\Customizer\Panel;

use SilverWp\Customizer\Section\LayoutFooter;
use SilverWp\Customizer\Section\LayoutHeader;
use SilverWp\Customizer\Section\LayoutMenu;
use SilverWp\Customizer\Section\Logo;
use SilverWp\Customizer\Section\TopBar;
use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\Customizer\Panel\Layout' ) ) {
    /**
     * Panel Layout
     * @category WordPress
     * @package SilverWp
     * @subpackage Customizer\Section
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Layout extends PanelAbstract {
        protected $panel_id = 'layout';

        /**
         * An associative array with panel params:
         * array(
         *  'priority'       => 10,
         *  'capability'     => 'edit_theme_options',
         *  'theme_supports' => '',
         *  'title'          => __('Theme Options', 'mytheme'),
         *  'description'    => __('Several settings pertaining my theme', 'mytheme'),
         * )
         *
         * @return array
         * @access public
         */
        public function createPanelParams() {
            $params = array(
                'priority'       => 9,
                'capability'     => 'edit_theme_options',
                'theme_supports' => '',
                'title'          => Translate::translate('Layout'),
                //'description'    => Translate::translate('Several settings pertaining my theme'),
            );
            return $params;
        }

        protected function createSections() {
            $this->addSection( new Logo() );
            $this->addSection( new TopBar() );
            $this->addSection( new LayoutHeader() );
            $this->addSection( new LayoutMenu() );
            $this->addSection( new LayoutFooter() );
        }
    }
}