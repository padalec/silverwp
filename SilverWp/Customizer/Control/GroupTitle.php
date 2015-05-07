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

if ( ! class_exists( 'SilverWp\Customizer\Control\GroupTitle' ) ) {

    /**
     * Group title control field
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Customizer\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class GroupTitle extends ControlAbstract {
        protected $type = 'group_title';
        protected $is_less_variable = false;

        public function __construct( $control_name ) {
            parent::__construct( $control_name );
            //fix kirki generate notice when default value isn't sets
            $this->setDefault( '' );
        }
    }
}