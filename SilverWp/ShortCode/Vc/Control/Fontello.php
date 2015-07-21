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
namespace SilverWp\ShortCode\Vc\Control;

use SilverWp\Helper\MetaBox;

if ( ! class_exists( '\SilverWp\ShortCode\Vc\Control\Fontello' ) ) {

    /**
     * Visual composer settings form element with fontello icons.
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage ShortCode\Vc\Control
     * @author Michal Kalkowski <michal at dynamite-studio.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Id: Fontello.php 2415 2015-02-11 13:49:13Z padalec $
     */
    class Fontello extends ControlMultiAbstract {
        protected $type = 'dropdown';

        public function __construct( $name ) {
            parent::__construct( $name );
            //add fontello icons
            $icons = MetaBox::getFontelloIcons();
            $this->setOptions( $icons );
        }
    }
} 