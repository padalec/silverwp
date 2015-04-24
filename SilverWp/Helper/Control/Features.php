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
namespace SilverWp\Helper\Control;

if ( ! class_exists( 'SilverWp\Helper\Control\Features' ) ) {

    /**
     *
     * Features controls group
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Helper\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Features extends Group {

        /**
         *
         * Class constructor
         *
         * @param string $name
         *
         * @access public
         */
        public function __construct( $name ) {
            parent::__construct( $name );
            $this->setRepeating( true );
            $this->setSortable( true );
            $this->setLabel( Translate::translate( 'Gallery' ) );

            $name = new Text( 'name' );
            $name->setLabel( Translate::translate( 'Name' ) );

            $this->addControl( $name );

            $values = new Textarea( 'values' );
            $values->setLabel( Translate::translate( 'Description' ) );
            $this->addControl( $values );
        }
    }
}