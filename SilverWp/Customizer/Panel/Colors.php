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

use SilverWp\Customizer\Section\Footer;
use SilverWp\Customizer\Section\General;
use SilverWp\Customizer\Section\Header;
use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\Customizer\Panel\Colors' ) ) {

    /**
     * Panel colors
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage SilverWp\Customizer\Section
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Colors extends PanelAbstract {

        /**
         * Panel name (unique ID)
         *
         * @var string
         */
        protected $panel_id = 'colors';

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
                'priority'       => 11,
                'capability'     => 'edit_theme_options',
                'theme_supports' => '',
                'title'          => Translate::translate( 'Colors' ),
                //'description'    => Translate::translate('Several settings pertaining my theme'),
            );
            return $params;
        }

        /**
         * Add sections to panel
         *
         * @access protected
         */
        protected function createSections() {
            $this->addSection( new Header() );
            $this->addSection( new General() );
            $this->addSection( new Footer() );
        }
    }
}