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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Sidebar/SidebarAbstract.php $
  Last committed: $Revision: 2581 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-03-17 09:57:29 +0100 (Wt, 17 mar 2015) $
  ID: $Id: SidebarAbstract.php 2581 2015-03-17 08:57:29Z padalec $
 */

namespace SilverWp\Sidebar;

use Roots_Wrapping;
use SilverWp\SingletonAbstract;
use SilverWp\Translate;
use SilverWp\Sidebar\Exception;

/**
 * Sidebar Abstract
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: SidebarAbstract.php 2581 2015-03-17 08:57:29Z padalec $
 * @category WordPress
 * @package Sidebar
 * @copyright (c) 2009 - 2014, SilverSite.pl
 * @abstract
 */
abstract class SidebarAbstract extends SingletonAbstract implements SidebarInterface {
    /**
     * unique id of sidebar
     *
     * @var string
     * @access protected
     */
    protected $id = null;
    /**
     * sidebar label
     *
     * @var string
     * @access protected
     */
    protected $name = '';
    /**
     *
     * @var string
     * @access protected
     */
    protected $description = '';
    /**
     * css class displayed in sidebar div
     *
     * @var string
     * @access protected
     */
    protected $css_class = '';

    /**
     * class constructor
     * register sitebar
     *
     * @access protected
     */
    protected function __construct() {
        $this->setName();
        $this->setDescription();
        try {
            $args = $this->getArgs();
            \register_sidebar( $args );
        } catch ( Exception $ex ) {
            echo $ex->displayAdminNotice();
        }
    }

    /**
     * unregister sidebar
     *
     * @param string $id
     *
     * @return void
     * @static
     */
    public static function unRegister( $id ) {
        unregister_sidebar( $id );
    }

    /**
     * get the uniqe id of sidebar
     *
     * @return string
     */
    public function getId() {
        return 'sidebar-' . strtolower($this->id);
    }

    /**
     * get the css class of sidebar
     *
     * @return string
     * @access public
     */
    public function getCssClass() {
        return $this->css_class;
    }

    /**
     *
     * get name of sidebar
     *
     * @return string translated name label
     * @access protected
     * @abstract
     * @since 1.8
     */
    abstract protected function setName();

    /**
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     *
     * set sidbar descripotion $this->description = Translate::translate()
     *
     * @return string translated description of sidebar
     * @access protected
     * @abstract
     * @since 1.8
     */
    abstract protected function setDescription();

    /**
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     *
     * get all arguments used for register_sidebar function
     *
     * @return array
     * @throws \SilverWp\Sidebar\Exception
     * @access private
     */
    private function getArgs() {
        if ( ! isset( $this->id ) || \is_null( $this->id ) ) {
            throw new Exception( Translate::translate( '$id variable is required and can\'t be empty.' ) );
        }

        $args = array(
            'name'          => $this->getName(),
            'id'            => $this->getId(),
            'description'   => $this->getDescription(),
            'class'         => $this->getCssClass(),
            'before_widget' => $this->beforeWidget(),
            'after_widget'  => $this->afterWidget(),
            'before_title'  => $this->beforeTitle(),
            'after_title'   => $this->afterTitle(),
        );

        return $args;
    }

    /**
     * html open tag displayed
     * before widget area
     *
     * @return string
     * @access public
     */
    public function beforeWidget() {
        return '<section class="widget %1$s %2$s">';
    }

    /**
     * html closing tag displayed
     * after widget area
     *
     * @return string
     * @access public
     */
    public function afterWidget() {
        return '</section>';
    }

    /**
     *
     * html tag displayed before sidebar title
     *
     * @return string
     * @access public
     */
    public function beforeTitle() {
        return '<h3>';
    }

    /**
     * html closing tag displayed after title
     *
     * @return string
     * @access public
     */
    public function afterTitle() {
        return '</h3>';
    }

    /**
     * load sidebar template
     *
     * @param string $template_name template name
     *
     * @return string
     * @static
     */
    public static function loadSidebarTemplate( $template_name ) {
        $template = include ( new Roots_Wrapping( 'templates/sidebar-' . $template_name ) );

        return $template;
    }

    /**
     *
     * Count widget in sidebar
     *
     * @param string $id sidebar id
     *
     * @return integer widget count in sidebar
     * @access public
     * @since $Revision: 2581 $
     */
    public function getWidgetCount( $id = null ) {
        $the_sidebars = \wp_get_sidebars_widgets();
        $sidebar_id   = is_null( $id ) ? $this->getId() : $id;
        if ( isset( $the_sidebars[ $sidebar_id ] ) ) {
            $widget_count = \count( $the_sidebars[ $this->getId() ] );
        } else {
            $widget_count = 0;
        }

        return (int) $widget_count;
    }
}
