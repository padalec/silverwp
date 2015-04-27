<?php

/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/libs/ssvafpress/classes/control/field/fontico.php $
  Last committed: $Revision: 2365 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-02-06 14:54:57 +0100 (Pt, 06 lut 2015) $
  ID: $Id: fontico.php 2365 2015-02-06 13:54:57Z padalec $
 */

/**
 * Control select field for Klico font selecter
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: fontico.php 2365 2015-02-06 13:54:57Z padalec $
 * @category Classes
 * @package Control
 * @subpackage Filed
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */

class VP_Control_Field_fontico extends VP_Control_Field_Fontawesome {
    public function __construct() {
        parent::__construct();
        $this->add_scripts();
         
    }
    
    public function render( $is_compact = false ) {
        $this->_setup_data();
        $this->add_data( 'is_compact', $is_compact );
        return VP_View::instance()->load( 'control/fontawesome', $this->get_data() );
    }

    public static function withArray( $arr = array(), $class_name = null ) {
        if( is_null( $class_name ) ) {
            $instance = new self();
        }else{
            $instance = new $class_name;
        }
        
        $instance->_basic_make( $arr );

        return $instance;
    }
    
    /**
     * add js script for display font select
     */
    public function add_scripts(){
        wp_enqueue_style( 'fontico', SILVERWP_THEME_URL . '/lib/SilverWp/libs/vafpress/public/css/vendor/select2.css' );
        wp_register_script( 'fontico', SILVERWP_THEME_URL . '/lib/SilverWp/libs/vafpress/public/js/vendor/select2.min.js', array(), false, true );
        wp_enqueue_script( 'fontico' );
        
    }
}

