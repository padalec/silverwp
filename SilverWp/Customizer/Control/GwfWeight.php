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
namespace SilverWp\Customizer\Control;

use SilverWp\Customizer\Section\SectionAbstract;

if ( ! class_exists( 'SilverWp\Customizer\Control\GwfWeight' ) ) {

    /**
     *
     * Google web font weight control
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Customizer\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class GwfWeight extends Radio {
        public function __construct( $control_name ) {
            parent::__construct( $control_name );
            $weight = SectionAbstract::flipSourceData( silverwp_font_weight_full() );
            $this->setOptions( $weight );
        }
    }
}