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
namespace SilverWp\Customizer\Section;


/**
 * Customizer Section interface
 *
 * @category WordPress
 * @package SilverWp
 * @subpackage Wp\Customizer\Sections
 * @author Michal Kalkowski <michal at silversite.pl>
 * @copyright Dynamite-Studio.pl & silversite.pl 2015
 * @version $Revision:$
 */
interface SectionInterface {
    /**
     * Get section name
     *
     * @return string
     * @access public
     */
    public function getName();

    /**
     * Get all controls list
     *
     * @param array $controls
     *
     * @return array
     * @access public
     */
    public function registerControls( array $controls );

    /**
     * Get all registered controls for section
     *
     * @return array
     * @access public
     */
    public function getControls();

    /**
     * Set panel unique id
     *
     * @param string $panel_id
     *
     * @return $this
     * @access public
     */
    public function setPanelId( $panel_id );
}
