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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/libs/ssvafpress/classes/quickeditform.php $
  Last committed: $Revision: 1852 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2014-11-28 21:03:24 +0100 (Pt, 28 lis 2014) $
  ID: $Id: quickeditform.php 1852 2014-11-28 20:03:24Z padalec $
 */

/**
 * Add meta box to Quick edit form
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: quickeditform.php 1852 2014-11-28 20:03:24Z padalec $
 * @category WordPRess
 * @package SilverWP
 * @subpackage MetBox
 * @copyright 2009 - 2014-03-14 SilverSite.pl
 */

class VP_QuickEditForm extends \VP_Metabox
{
    /**
     * post type name
     * 
     * @var string
     * @access protected 
     */
    protected $post_type_name;
    
    /**
     * class constructor
     * 
     * @param array $attributes
     * @access public
     */
    public function __construct(array $attributes)
    {
        if (!is_array($attributes) && file_exists($attributes)) {
            $attributes = include $attributes;
        }
        
        $this->WPAlchemy_MetaBox($attributes);
                    
        $this->addScripts();
        
        if ($this->can_output()) {
            // make sure metabox template loaded
            if (!is_array($this->template) && \file_exists($this->template)) {
                $this->template = include $this->template;
            }
            //\add_filter('manage_' . $this->post_type_name. '_posts_columns', array( $this, 'managingPostsColumns' ), 10, 1);
            \add_action('bulk_edit_custom_box', array($this, 'addFields'), 10, 2);
            \add_action('quick_edit_custom_box', array($this, 'addFields'), 10, 2);
            \add_action('save_post', array($this, '_save'), 10, 2);
            

        }
        \add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
        self::$pool[ $this->id ] = $this;
    }
    
    /**
     * 
     * add custom coloumn to edit.php 
     * this shouldby run by unrem
     * \add_filter('manage_' . $this->post_type_name. '_posts_columns', array( $this, 'managingPostsColumns' ), 10, 1);
     * in constructor but we can add column only after date so I decaid to 
     * add column by edit SilverWp\MetaBox\MetaBoxAbstract::setColumns method
     * and add column on end of table
     * 
     * @param array $columns - defualt columns list
     * @return array
     */
    public function managingPostsColumns($columns)
    {
        $columns['silverwp_custom_column'] = '';
        return $columns;
    }
    
    /**
     * 
     * add field to quick form
     * 
     * @param string $column_name
     * @param string $post_type
     */
    public function addFields($column_name, $post_type)
    {
        if ($this->post_type_name === $post_type && $column_name == 'silverwp_custom_column') {
            echo '<fieldset class="inline-edit-col-left">';
            echo '<div class="inline-edit-col">';
            $loader = \VP_WP_Loader::instance();
            $loader->add_types($this->get_field_types(), 'quickeditform');
            $this->_setup();
            echo '</div>';
            echo '</fieldset>';
        }
    }
    /**
     * add requireds css and js scripts
     * 
     * @access private
     * @return void
     */
    private function addScripts()
    {
        $loader = \VP_WP_Loader::instance();
        $loader->add_main_css('vp-metabox');
    }
    /**
     * enqueue js scripts
     * 
     * @access public
     * @return void
     */
    public function enqueueScripts()
    {
        //\wp_enqueue_script('vp-quickeditform', get_template_directory_uri() . '/assets/js/silverwp-admin.js');
    }
}
