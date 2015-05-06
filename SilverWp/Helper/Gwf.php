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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/Gwf.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: Gwf.php 2184 2015-01-21 12:20:08Z padalec $
 */

namespace SilverWp\Helper;

use SilverWp\FileSystem;
use SilverWp\SilverWp;

/**
 * Google Web Fonts Helper
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: Gwf.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
class Gwf extends \VP_Site_GoogleWebFont
{
    /**
     *
     * google font file name
     *
     * @var string
     */
    private $font_file = 'gwf.json';
    /**
     *
     * json object with all google fonts
     *
     * @var object
     */
    private $fonts = null;
    /**
     *
     * @var object
     * @static
     */
    private static $instance = null;

    /**
     *
     * Class constructor
     *
     * @access protected
     */
    protected function __construct()
    {
        $fonts_file = \file_get_contents($this->getFontFile());
        $this->fonts = \json_decode($fonts_file);
    }
    /**
     * singleton object instance
     *
     * @return object Gwf class instance
     * @static
     * @access public
     */
    final public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     *
     * path to file this function can be overwriten
     *
     * @return string
     * @access protected
     */
    protected function getFontFile()
    {
        $file_system = FileSystem::getInstance();

        $data_source_path = $file_system->getDirectories( 'data' );
        return $data_source_path . '/' . $this->font_file;
    }
    /**
     *
     * check if font exists in json file
     *
     * @param string $font_face
     * @return boolean
     */
    public function fontExists($font_face)
    {
        if (\property_exists($this->fonts, $font_face)) {
            return true;
        }
        return false;
    }
    /**
     *
     * get all value of $attribute_name for $font_face
     *
     * @param string $font_face
     * @param string $attribute_name weight, style
     * @return array
     */
    public function getFontAttribute($font_face, $attribute_name)
    {
        if ($this->fontExists($font_face)) {
            return $this->fonts->{$font_face}->{$attribute_name};
        }
        return array();
    }
    /**
     * get gogole font family name
     *
     * @return array
     * @access public
     */
    public function getFontFamily()
    {
        $fonts = \array_keys(\get_object_vars($this->fonts));
        return $fonts;
    }
    /**
     * get font link
     *
     * @param string $face font face name
     * @param string $style
     * @param string $subset
     * @param string $weight
     * @return string url to google web font subset
     */
    public function getFontLink($face, $style, $subset, $weight = 'normal')
    {
        $this->add($face, $weight, $style, $subset);
        $list_links = $this->get_font_links();
        $links = \reset($list_links);
        return $links;
    }
}
