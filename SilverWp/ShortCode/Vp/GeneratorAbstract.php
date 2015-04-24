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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/ShortCode/Generator/GeneratorAbstract.php $
  Last committed: $Revision: 2365 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-02-06 14:54:57 +0100 (Pt, 06 lut 2015) $
  ID: $Id: GeneratorAbstract.php 2365 2015-02-06 13:54:57Z padalec $
 */

namespace SilverWp\ShortCode\Vp;

use SilverWp\CoreInterface;
use SilverWp\Debug;
use SilverWp\ShortCode\Vp\Menu\MenuInterface;
use SilverWp\SingletonAbstract;
use SilverWp\Translate;
use VP_ShortcodeGenerator;

/**
 * Description of GeneratorAbstract
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: GeneratorAbstract.php 2365 2015-02-06 13:54:57Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage ShortCode\Generator
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
abstract class GeneratorAbstract extends SingletonAbstract implements GeneratorInterface, CoreInterface {

    /**
     * unique name
     * It's possible to have more than one Shortcode Generator in a page,
     * therefore a unique name is required to differentiate each of them.
     *
     * @var string
     * @access protected
     */
    protected $name;

    /**
     *
     * You can define to what post types the Shortcode Generator
     * will be added, this will default to post and page in array format.
     *
     * @var array
     * @access protected
     */
    protected $post_type = array( 'post', 'page' );

    /**
     * ShortCode generator will only be added to post or
     * page editing, if you wish to add Shortcode Generator
     * to other pages, for example your own option page,
     * then you can add the hook suffix or screen id of
     * your page in this parameter.
     *
     * @var array
     */
    protected $included_pages = array( 'appearance_page_vpt_option' );

    /**
     *
     * VP_ShortcodeGenerator object handler
     *
     * @var object
     * @access private
     * @static
     */
    private static $vp_short_code_generator = null;

    /**
     * List of menus
     *
     * @var array
     * @access private
     */
    private $menu = array();

    /**
     *
     * @var string
     */
    private $modal_title;

    /**
     *
     * @var string
     */
    private $button_title;

    /**
     *
     * Url to short code image displayed in Tiny editor
     *
     * @var string
     */
    private $main_image;

    /**
     *
     * Url to sprite image
     *
     * @var string
     */
    private $sprite_image;

    /**
     * Class constructor
     *
     * @throws Exception
     * @access protected
     */
    protected function __construct() {
        if ( ! isset( $this->name ) ) {
            throw new Exception(
                Translate::translate( 'Class attribute $name is required and can\'t be empty.' )
            );
        }
        add_action( 'after_setup_theme', array( $this, 'init' ) );
    }

    /**
     *
     * Get all added menu for short code generator
     *
     * @return array
     * @access public
     * @final
     */
    final public function getMenu() {
        $this->createMenu();
        $menu = array();
        foreach ( $this->menu as $menu_class ) {
            $menu[ $menu_class->getTitle() ] = $menu_class->getElements();
        }

        return $menu;
    }

    /**
     * Init short code generator
     * create VP_ShortcodeGenerator object
     *
     * @access public
     */
    public function init() {
        $args = array(
            'name'           => $this->name,
            'template'       => $this->getMenu(),
            'types'          => $this->post_type,
            'included_pages' => $this->included_pages,
        );

        if ( isset( $this->main_image ) ) {
            $args[ 'main_image' ] = $this->main_image;
        }

        if ( isset( $this->sprite_image ) ) {
            $args[ 'sprite_image' ] = $this->sprite_image;
        }

        if ( isset( $this->modal_title ) ) {
            $args[ 'modal_title' ] = $this->modal_title;
        }

        if ( isset( $this->button_title ) ) {
            $args[ 'button_title' ] = $this->button_title;
        }

        self::$vp_short_code_generator = new VP_ShortcodeGenerator( $args );
    }

    /**
     * Short code generator unique name
     *
     * @return string
     * @access public
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Return instance of VP_ShortcodeGenerator class
     *
     * @return object VP_ShortcodeGenerator class instance
     * @static
     * @access public
     */
    public static function getVpShortCodeGenerator() {
        return self::$vp_short_code_generator;
    }

    /**
     * Add new menu too short code generator UI
     *
     *
     * @param \SilverWp\ShortCode\Vp\Menu\MenuInterface $menu_class
     *
     * @return $this|void
     * @throws \SilverWp\ShortCode\Vp\Exception
     * @access public
     * @final
     */
    final public function addMenu( MenuInterface $menu_class ) {
        if ( \in_array( $menu_class, $this->menu ) ) {
            return $this;
        }
        $this->menu[ ] = $menu_class;

        return $this;
    }

    /**
     * Set modal title
     *
     * @access public
     * @abstract
     * @return string translated modal title displayed in WP Editor
     */
    public function setModalTitle( $modal_title ) {
        $this->modal_title = $modal_title;

        return $this;
    }

    /**
     *
     * @param $button_title
     *
     * @return $this
     * @access
     */
    public function setButtonTitle( $button_title ) {
        $this->button_title = $button_title;

        return $this;
    }

    /**
     *
     * This method is used to add short code settings form to generator
     *
     * @return void
     * @access protected
     * @abstract
     */
    abstract protected function createMenu();

    /**
     * Set main short code image displayed in wp editor
     *
     * @return string
     * @access public
     */
    public function setMainImage( $image_url ) {
        $this->main_image = $image_url;

        return $this;
    }

    /**
     * Set sprite image
     *
     * @return string
     * @access public
     */
    public function setSpriteImage( $image_url ) {
        $this->sprite_image = $image_url;

        return $this;
    }
}
