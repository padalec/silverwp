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

if ( ! class_exists( 'SilverWp\Helper\Control\CodeEditor' ) ) {
    /**
     *
     * As it's name implies, this control is used to
     * write code with the power of Ace Editor syntax highlighting,
     * error checking and etc. Supports several common programming
     * language and includes all themes from Ace Editor.
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage Helper\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class CodeEditor extends ControlAbstract {

        protected $type = 'codeeditor';

        /**
         * Editor's theme:
         * chaos, chrome, clouds, clouds_midnight, cobalt, crimson_editor, dawn,
         * dreamweaver, eclipse, github, mono_industrial, monokai, solarized_dark,
         * solarized_light, textmate, twilight.
         *
         * @param string $theme
         *
         * @return $this
         * @access public
         */
        public function setTheme( $theme ) {
            $this->setting[ 'theme' ] = $theme;

            return $this;
        }

        /**
         *
         * Language mode: javascript, css, html, php, json, xml, markdown.
         *
         * @param string $mode
         *
         * @access public
         * @return $this
         */
        public function setMode( $mode ) {
            $this->setting[ 'mode' ] = $mode;

            return $this;
        }
    }
}