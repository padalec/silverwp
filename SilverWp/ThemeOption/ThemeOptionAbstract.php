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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ThemeOption/ThemeOptionAbstract.php $
  Last committed: $Revision: 2568 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-03-13 15:28:41 +0100 (Pt, 13 mar 2015) $
  ID: $Id: ThemeOptionAbstract.php 2568 2015-03-13 14:28:41Z padalec $
 */

namespace SilverWp\ThemeOption;

use SilverWp\Debug;
use SilverWp\FileSystem;
use SilverWp\SingletonAbstract;
use SilverWp\ThemeOption\Menu\MenuAbstract;
use SilverWp\ThemeOption\ThemeOptionInterface;
use SilverWp\Translate;
use VP_Option;

/**
 * Theme Option Abstract
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: ThemeOptionAbstract.php 2568 2015-03-13 14:28:41Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage ThemeOption
 * @copyright (c) 2014, SilverSite.pl
 * @abstract
 */
abstract class ThemeOptionAbstract extends SingletonAbstract implements ThemeOptionInterface {
    /**
     *
     * Whether the development mode is active or not. You should activate this
     * when you are still working and testing on the builder.
     * This mode will prevent the framework to save your meatbox into WordPress
     * Database and take the default value set in your meta_boxes instead.
     * Default to FALSE.
     *
     * @staticvar boolean
     */
    const DEV_MODE = SILVERWP_THEME_OPTIONS_DEV;

    /**
     *
     * all labels displayed in options admin page
     *
     * @var array
     * @access protected
     */
    protected $labels = array(
        'page_title' => 'SilverWp Theme Options',
        'menu_label' => 'Theme Options',
    );
    /**
     * slug in admin menu
     *
     * @var string
     * @access protected
     */
    protected $menu_slug = 'theme_options';
    /**
     *
     * Parent menu slug string (reference) or supply array
     * (can contains 'icon_url' & 'position') for top level menu, for example:
     * 'menu_page' => array(
     *       'icon_url' => get_template_directory_uri() . '/img/icon.png',
     *       'position' => 6,
     *   ),
     *
     * @link http://codex.wordpress.org/Function_Reference/add_menu_page
     * @var array
     * @access protected
     */
    protected $menu_page = array(
        'position' => 61,
    );
    /**
     *
     * The minimum user roles allowed to access the options page.
     * Default to edit_theme_options
     *
     * @var string
     * @access protected
     */
    protected $min_role = 'edit_theme_options';
    /**
     * Whether the fallback compatibility for auto naming
     * for Menu will be enabled or not. If this is enabled,
     * and you don't specify a menu name in the builder, the
     * framework will auto-name it with something
     * like, "menu_1", "menu_2", "menu_3". Default to TRUE.
     *
     * @var boolean
     * @access protected
     */
    protected $use_auto_group_naming = true;
    /**
     *
     * Whether the export and import menu will be displayed
     * at the options panel. Default to TRUE.
     *
     * @var boolean
     * @access protected
     */
    protected $use_exim_menu = true;
    /**
     *
     * The slug name for the parent menu (or the file name of a standard WordPress admin page).
     *
     * @link http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters $parent_slug section
     * @var string
     */
    protected $parent_slug = array();
    /**
     *
     * @var array
     * @access private
     */
    private $menu = array();

    /**
     *
     * Start up class constructor
     *
     * @access public
     */
    protected function __construct() {
        $this->setLabels();
        add_action( 'after_setup_theme', array( $this, 'init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'addCss' ) );
    }

    /**
     * add custom css file
     */
    public function addCss() {
        $assets_uri = $this->getAssetsUri();
        wp_register_style( 'theme_options', $assets_uri . 'css/theme_options.css', array( 'vp-option' ), SILVERWP_VER );
        wp_enqueue_style( 'theme_options' );
    }

    /**
     *
     * Get URI to assets folder
     *
     * @return string
     * @access public
     */
    public function getAssetsUri() {
        $file_system = FileSystem::getInstance();
        return $file_system->getDirectories('assets_uri');
    }

    /**
     *
     * Register and add menu settings
     *
     * @access public
     */
    public function init() {
        $option = array(
            'is_dev_mode'           => self::DEV_MODE,
            'option_key'            => THEME_OPTION_PREFIX,
            'page_slug'             => SILVERWP_THEME_TEXT_DOMAIN . '-' . $this->menu_slug,
            'template'              => $this->getTemplate(),
            //'menu_page'             => $this->getMenuPage(),
            'use_auto_group_naming' => $this->use_auto_group_naming,
            'use_exim_menu'         => $this->use_exim_menu,
            'minimum_role'          => $this->min_role,
            'layout'                => 'fixed',
            'page_title'            => $this->labels[ 'page_title' ],
            'menu_label'            => $this->labels[ 'menu_label' ],
        );
        new VP_Option( $option );
    }

    /**
     *
     * Set labels
     *
     * @abstract
     * @since 0.2
     */
    abstract protected function setLabels();

    /**
     *
     * This method is used to define option page.
     * Use addMenu inside to add new menu page
     *
     * @return void
     * @access protected
     * @since 0.3
     */
    abstract protected function createOptions();

    /**
     *
     * get menu page options
     *
     * @return array
     * @access public
     */
    public function getMenuPage() {
        $menu_page = array(
            'icon_url' => \get_template_directory_uri() . '/assets/img/admin/menu_icon/silverwp-icon.png',
        );
        if ( count( $this->parent_slug ) ) {
            $menu_page[ 'parent_slug' ] = \THEME_CONTEXT . '-' . $this->menu_slug;
            $menu_page[ 'page_title' ]  = $this->parent_slug[ 'page_title' ];
        }

        return \array_merge_recursive( $this->menu_page, $menu_page );
    }

    /**
     * get theme options template
     *
     * @return array
     * @access private
     * @since 1.9
     */
    private function getTemplate() {
        $this->createOptions();
        $template = array(
            'title' => $this->labels[ 'page_title' ],
            'logo'  => $this->getLogo(),
            'menus' => $this->getMenu(),
        );
        return $template;
    }

    /**
     * theme options logo
     *
     * @access protected
     * @return string
     * @since 1.9
     */
    protected function getLogo() {
        $logo = \get_template_directory_uri() . '/assets/img/admin/logo_theme_option_panel.png';

        return $logo;
    }

    /**
     * add new menu page
     *
     * @param \SilverWp\ThemeOption\Menu\MenuAbstract $menu_class
     *
     * @return \SilverWp\ThemeOption\ThemeOptionAbstract
     * @throws Exception
     * @access public
     */
    public function addMenu( MenuAbstract $menu_class ) {
        if ( ! ( $menu_class instanceof MenuAbstract ) ) {
            throw new Exception(
                Translate::params(
                    'Class %s isn\'t instance of SilverWp\ThemeOption\Menu\MenuAbstract',
                    $menu_class
                )
            );
        }
        if ( in_array( $menu_class, $this->menu ) ) {
            return;
        }
        $this->menu[ ] = $menu_class;

        return $this;
    }

    /**
     *
     * get all added menus and return array
     *
     * @return array
     * @access private
     */
    public function getMenu() {
        foreach ( $this->menu as $menu_class ) {
            $menu[ ] = $menu_class->getSettings();
        }
        return $menu;
    }
}
