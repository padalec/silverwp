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
 Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ShortCode/Form/Element/ElementInterface.php $
 Last committed: $Revision: 2184 $
 Last changed by: $Author: padalec $
 Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
 ID: $Id: ElementInterface.php 2184 2015-01-21 12:20:08Z padalec $
*/
namespace SilverWp\ShortCode\Vc\Control;


/**
 * Control setting form interface
 *
 * @category WordPress
 * @package SilverWp
 * @subpackage ShortCode\Vc\Form\Element
 * @author Michal Kalkowski <michal at dynamite-studio.pl>
 * @copyright Dynamite-Studio.pl & silversite.pl 2014
 * @version $Id: ElementInterface.php 2184 2015-01-21 12:20:08Z padalec $
 * @link https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524332
 */
interface ControlInterface {
    /**
     * Class name that will be added to the "holder" HTML tag.
     * Useful if you want to target some CSS rules to specific items in the backend edit interface
     *
     * @param string $css_class
     *
     * @return $this
     * @access public
     */
    public function setCssClass( $css_class );

    /**
     * Set param container width in content element edit window.
     * According to Bootstrap logic eg. col-md-4. (Available from Visual Composer 4.0)
     *
     * @param string $edit_field_class
     *
     * @return $this
     * @access public
     */
    public function setEditFieldClass( $edit_field_class );

    /**
     * Params with greater weight will be rendered first. (Available from Visual Composer 4.4)
     *
     * @param int $weight
     *
     * @return $this
     * @access public
     */
    public function setWeight( $weight );

    /**
     * Use it to divide your params within groups (tabs)
     *
     * @param string $group
     *
     * @return $this
     * @access public
     */
    public function setGroup( $group );

    /**
     * HTML tag name where Visual Composer will store attribute value in Visual Composer edit mode.
     * Default: hidden input
     *
     * @param string $holder
     *
     * @return $this
     * @access public
     */
    public function setHolder( $holder );

    /**
     * Show value of param in Visual Composer editor
     *
     * @param boolean $label
     *
     * @return $this
     * @access pubic
     */
    public function setAdminLabel( $label );

    /**
     * Set param_holder_class attribute
     *
     * @param string $css_class
     *
     * @return $this
     * @access public
     */
    public function setParamHolder( $css_class );
}