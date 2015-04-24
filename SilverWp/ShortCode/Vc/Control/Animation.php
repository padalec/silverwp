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
namespace SilverWp\ShortCode\Vc\Control;

use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\ShortCode\Vc\Control\Animation' ) ) {

    /**
     * Visual composer settings form element with css animation
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage ShortCode\Vc\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Animation extends Select {

        /**
         *
         * Class constructor
         *
         * @param string $name control name
         *
         * @throws \SilverWp\Helper\Control\Exception
         */
        public function __construct( $name = 'css_animation' ) {
            parent::__construct( $name );

            $this->setLabel( Translate::translate( 'Css animation' ) );
            $animation_list = silverwp_get_button_css_animation();
            $this->setOptions( $animation_list );
            $this->setDescription(
                Translate::translate(
                    'Select type of animation if you want this element to be animated when it
                    enters into the browsers viewport. Note: Works only in modern browsers.'
                )
            );
        }
    }
}