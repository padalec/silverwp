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

namespace SilverWp\MetaBox;

/**
 * meta box interface
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: MetaBoxInterface.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage MetaBox
 */
interface MetaBoxInterface
{
    /**
     * get single meta box
     * 
     * @param string $name meta box name
     * @param boolean $remove_first
     * @return mixed meta box value
     * @access public
     */
    public function getSingle($name, $remove_first = true);
    /**
     * get all meta box for single post type
     * @access public
     * @return array array with all meta boxes
     */
    public function getAll();
    /**
     * set meta box uniqe id
     * 
     * @param string $id post type name
     * @access public
     */
    public function setId($id);
    /**
     * init meta box and setsup
     */
    public function init();
    /**
     * Add columns to edit screen
     *
     * @link http://wptheming.com/2010/07/column-edit-pages/
     * @access public
     * @return array
     */
    public function setColumns($columns);
    /**
     *
     * display data in columns in edit Screen
     *
     * @param int $columns
     * @link http://wpengineer.com/display-post-thumbnail-post-page-overview
     * @access public
     * @return void
     */
    public function columnDisplay($columns, $post_id);
    
    /**
     *
     * Add count to "Right Now" Admin Dashboard Widget
     *
     * @access public
     * @return void
     * @deprecated since version 1.8
     */
    //public function addCounts();
}
