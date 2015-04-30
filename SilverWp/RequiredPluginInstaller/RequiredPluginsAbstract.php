<?php
/*
 * Copyright (C) 2014 Michal Kalkowski <michal at silversite.pl>
 *
 * RequiredPluginInstaller is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * RequiredPluginInstaller is distributed in the hope that it will be useful,
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
namespace RequiredPluginInstaller;

if ( ! class_exists( 'RequiredPluginInstaller\RequiredPluginsAbstract' ) ) {

    /**
     * Required plugins install
     *
     * @category WordPress
     * @package RequiredPluginInstaller
     * @subpackage Schedule
     * @author Michal Kalkowski <michal.kalkowski at dynamite-studio.pl>
     * @copyright Dynamite-Studio.pl 2015
     * @version $Revision:$
     * @abstract
     */
    abstract class RequiredPluginsAbstract extends SingletonAbstract implements CoreInterface {
        /**
         * Strings translations
         *
         * @var array
         */
        protected $strings = array();

        /**
         *
         * Plugins list
         *
         * @var array
         */
        protected $plugins = array();

        /**
         * Unique ID for hashing notices for multiple instances of TGMPA.
         *
         * @todo add exception wen this variable isn't set
         * @var string
         */
        protected $id;

        /**
         * Default absolute path to pre-packaged plugins.
         *
         * @var string
         */
        protected $default_path = '';

        /**
         *
         * Menu slug.
         *
         * @var string
         */
        protected $menu = 'tgmpa-install-plugins';

        /**
         * Show admin notices or not.
         *
         * @var bool
         */
        protected $has_notices = true;

        /**
         * If false, a user cannot dismiss the nag message.
         *
         * @var bool
         */
        protected $dismissible = false;

        /**
         * If 'dismissible' is false, this message will be output at top of nag.
         *
         * @var string
         */
        protected $dismiss_msg = '';

        /**
         * Automatically activate plugins after installation or not.
         *
         * @var bool
         */
        protected $is_automatic = false;

        /**
         * Message to output right before the plugins table.
         *
         * @var string
         */
        protected $message = '';

        /**
         * Class constructor
         *
         * @access protected
         */
        protected function __construct() {
            $this->strings = array(
                'page_title'                      => __( 'Install Required Plugins' ),
                'menu_title'                      => __( 'Install Plugins' ),
                'installing'                      => __( 'Installing Plugin: %s' ),
                // %s = plugin name.
                'oops'                            => __( 'Something went wrong with the plugin API.' ),
                'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.',
                                                                       'This theme requires the following plugins: %1$s.' ),
                // %1$s = plugin name(s).
                'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.',
                                                                       'This theme recommends the following plugins: %1$s.' ),
                // %1$s = plugin name(s).
                'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.',
                                                                       'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
                // %1$s = plugin name(s).
                'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.',
                                                                       'The following required plugins are currently inactive: %1$s.' ),
                // %1$s = plugin name(s).
                'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.',
                                                                       'The following recommended plugins are currently inactive: %1$s.' ),
                // %1$s = plugin name(s).
                'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.',
                                                                       'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
                // %1$s = plugin name(s).
                'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                                                                       'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ),
                // %1$s = plugin name(s).
                'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.',
                                                                       'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
                // %1$s = plugin name(s).
                'install_link'                    => _n_noop( 'Begin installing plugin',
                                                                       'Begin installing plugins' ),
                'activate_link'                   => _n_noop( 'Begin activating plugin',
                                                                       'Begin activating plugins' ),
                'return'                          => __( 'Return to Required Plugins Installer' ),
                'plugin_activated'                => __( 'Plugin activated successfully.' ),
                'complete'                        => __( 'All plugins installed and activated successfully. %s' ),
                // %s = dashboard link.
                'nag_type'                        => 'updated'
                // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
            );
            $this->init();
            add_action( 'tgmpa_register', array($this, 'activation') );
        }

        public function setString( $key, $label ) {
            $this->strings[ $key ] = $label;

            return $this;
        }

        public function addPlugin( $settings ) {
            $this->plugins[ ] = $settings;

            return $this;
        }

        public function activation() {
            $plugins = $this->plugins;
            $config  = array(
                'id'           => $this->id,
                // Unique ID for hashing notices for multiple instances of TGMPA.
                'default_path' => $this->default_path,
                // Default absolute path to pre-packaged plugins.
                'menu'         => $this->menu,
                // Menu slug.
                'has_notices'  => $this->has_notices,
                // Show admin notices or not.
                'dismissable'  => $this->dismissible,
                // If false, a user cannot dismiss the nag message.
                'dismiss_msg'  => $this->dismiss_msg,
                // If 'dismissable' is false, this message will be output at top of nag.
                'is_automatic' => $this->is_automatic,
                // Automatically activate plugins after installation or not.
                'message'      => $this->message,
                // Message to output right before the plugins table.
                'strings'      => $this->strings
            );
            \tgmpa( $plugins, $config );
        }

        /**
         * Check the plugin is activate
         *
         * @param string $plugin_name
         *
         * @return bool
         * @access public
         */
        public function isActivePlugin($plugin_name) {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            $plugin_path = $plugin_name . '/' . $plugin_name . '.php';
            $is_active = is_plugin_active( $plugin_path );
            return $is_active;
        }
    }
}