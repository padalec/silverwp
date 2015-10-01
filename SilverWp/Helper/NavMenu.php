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
namespace SilverWp\Helper;

use SilverWp\PostType\PostTypeAbstract;
use SilverWp\SingletonAbstract;
use SilverWp\Translate;
use SilverWp\Wpml\Wpml;

/**
 * Wp Nave Menu Helper
 * Fix active selected page for custom post types
 *
 * @author        Michal Kalkowski <michal at silversite.pl>
 * @version       0.6
 * @category      WordPress
 * @package       SilverWp
 * @subpackage    Helper
 * @copyright     SilverSite.pl (c) 2009 - 2014
 */
class NavMenu extends SingletonAbstract {
    /**
     * class constructor
     *
     * @access private
     */
    protected function __construct() {
        \add_filter( 'nav_menu_css_class', array( $this, 'addActive' ) );
        \add_filter( 'wp_nav_menu_items', array( $this, 'addSearchForm' ), 10, 2 );
        $this->registerMenu();
    }

    /**
     * Remove active class from menu
     *
     * @param string $css_class css class name
     *
     * @return bool
     */
    private function removeActiveClass( $css_class ) {
        $is_active = ( $css_class == 'active' ) ? false : true;

        return $is_active;
    }

    /**
     * Add active class to menu of post type single template
     *
     * @param array $css_classes
     *
     * @return array
     */
    public function addActive( $css_classes ) {
        $custom_post_type = PostType::get_custom_post_type();

        if ( \is_single() && \is_singular( $custom_post_type ) || \is_tax() ) {
            // check - Post is a Custom Post Type
            $css_classes = \array_filter( $css_classes, array( $this, 'removeActiveClass' ) );
            //get all registered custom post type templates
            foreach ( $custom_post_type as $post_type ) {
				if ( $post_type === get_post_type() ) {
                    $searching_slug = array();

                    $templates = PostTypeAbstract::getTemplates( $post_type );

	                if ( ! is_null( $templates ) ) {
	                    $pages = Page::getPageByTemplate( $templates );

	                    foreach ( $pages as $page ) {
		                    $slag             = sanitize_title( $page->post_title );
		                    $searching_slug[] = 'menu-' . $slag;
	                    }

	                    if ( array_intersect( $searching_slug, $css_classes ) ) {
		                    $css_classes[] = 'active';
	                    }
                    }

                }
            }
        }

        return $css_classes;
    }

    /**
     * add search button too nave menu
     *
     * @param string $items
     * @param object $args Std class object
     *
     * @return string
     * @access public
     */
    public function addSearchForm( $items, $args ) {
        if ( 'primary_navigation' === $args->theme_location ) {
            $items .= '<li class="menu-search">';
            $items .= '<a href="#search-form" id="nav-search-toggle">';
            $items .= '<i class="icon-search-1"></i>';
            $items .= '</a>';
            $items .= '</li>';
        }

        return $items;
    }

    /**
     * Register new menu
     *
     * @access public
     * @return void
     */
    public function registerMenu() {
        $menu = array(
            'footer' => Translate::translate( 'Footer menu' ),
        );
        register_nav_menus( $menu );
    }

    /**
     * add language switcher to nave menu
     *
     * @param string $items
     * @param object $args
     *
     * @return string
     * @access public
     */
    public function addLangSwitcher( $items, $args ) {
        if ( 'primary_navigation' === $args->theme_location ) {
            $items .= Wpml::langSwitcher();
        }

        return $items;
    }
}
