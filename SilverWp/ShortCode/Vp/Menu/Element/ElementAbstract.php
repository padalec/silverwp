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
namespace SilverWp\ShortCode\Vp\Menu\Element;

use SilverWp\Helper\Control\ControlInterface;
use SilverWp\ShortCode\ShortCodeAbstract;
use SilverWp\SingletonAbstract;

/**
 * Short Code Generator Menu Element Abstract
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: ElementAbstract.php 2591 2015-03-24 16:26:17Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage ShortCode\Generator\Menu\Element
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
abstract class ElementAbstract extends ShortCodeAbstract implements ElementInterface {
    /**
     * @var array
     */
    private $settings = array();

    /**
     * Class constructor
     *
     * @throws \SilverWp\ShortCode\Exception
     */
    public function __construct() {
        parent::__construct();

        if ( SingletonAbstract::isImplemented( get_called_class(), 'SilverWp\ShortCode\Vp\Menu\Element\AttributesInterface' ) ) {
            $this->createAttributes();
        }
    }

    /**
     * Get element name alias to getTagBase()
     *
     * @see getTagBase()
     * @return string
     * @access public
     */
    public function getName() {
        return $this->getTagBase();
    }

    /**
     *
     * Add control to form attribute
     *
     * @param \SilverWp\Helper\Control\ControlInterface $control
     *
     * @return $this
     * @access public
     */
    public function addAttribute( ControlInterface $control ) {
        $this->settings[ 'attributes' ][] = $control->getSettings();

        return $this;
    }

    /**
     *
     * Set element title
     *
     * @param string $title
     *
     * @return $this
     * @access public
     */
    public function setTitle( $title ) {
        $this->settings[ 'title' ] = $title;

        return $this;
    }

    /**
     *
     * Get element settings
     *
     * @return array
     * @access public
     */
    public function getSettings() {
        $this->settings[ 'code' ] = $this->getTag();

        return $this->settings;
    }
}
