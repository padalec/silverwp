<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/UpdateNotifier.php $
  Last committed: $Revision: 2601 $
  Last changed by: $Author: padalec $
  Last changed d9-03 17:54:46 +0200 (Śr, 03 wrz 2014) $
  ID: $Id: UpdateNotifier.php 2601 2015-04-02 09:07:29Z padalec $
 */

namespace SilverWp;

use SilverWp\Translate;
use SilverWp\Helper\Theme;

/**
 * get user information
 * about new version of theme
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: UpdateNotifier.php 2601 2015-04-02 09:07:29Z padalec $
 * @category WordPress
 * @package SilverWp
 * @link http://wplift.com/notify-your-theme-users-about-updates-in-their-dashboard
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
class UpdateNotifier extends SingletonAbstract
{
    /**
     *
     * name of notifier file
     *
     * @var string
     */
    private $notfier_xml_file = 'notifier.xml';
    /**
     * get theme information
     *
     * @param string $value
     * @return string
     */
    private function getThemeInfo($value)
    {
        $info = Theme::getThemeInfo($value);
        return $info;
    }
    /**
     *
     * get the notifier xml file url
     *
     * @return string
     * @access private
     */
    private function getNotifierXmlFile()
    {
        $theme_uri = $this->getThemeInfo('ThemeURI');
        return $theme_uri . $this->notfier_xml_file;
    }
    /**
     * class constructor
     *
     * @access public
     */
    protected function __construct()
    {
        add_action('admin_menu', array( $this, 'showInfoMenu' ));
    }
    /**
     *
     * Provides a notification everytime the theme is updated
     *
     * @link http://themeforest.net/user/unisphere Original code courtesy of João Araújo of Unisphere Design
     * @access public
     * @return void
     */
    public function showInfoMenu()
    {
        //This tells the function to cache the remote call for 21600 seconds (6 hours)
        $xml = $this->getLatestThemeVersion(21600);
        //Get theme data from style.css (current version is what we want)
        $theme_name = $this->getThemeInfo('Name');
        $theme_version = $this->getThemeInfo('Version');
        if ($xml && version_compare($theme_version, $xml->latest) == -1) {
            add_dashboard_page(
                $theme_name . Translate::translate('Theme Updates'),
                $this->notfierMenuHighlight($theme_name),
                'administrator',
                sanitize_title($theme_name) . '-updates',
                array( $this, 'showInfo' )
            );
        }
    }
    /**
     * 
     * @param type $theme_name
     * @return string
     */
    private function notfierMenuHighlight($theme_name)
    {
        $html  = $theme_name;
        $html .= '<span class="update-plugins count-1">';
        $html .= '<span class="update-count">';
        $html .= Translate::translate('New Updates');
        $html .= '</span></span>';
        return $html;
    }
    /**
     *
     * update notifier information
     *
     * @access public
     * @return void
     */
    public function showInfo()
    {
        // This tells the function to cache the remote call for 21600 seconds (6 hours)
        $xml = $this->getLatestThemeVersion(21600);
        // Get theme data from style.css (current version is what we want)
        $theme_data = wp_get_themes(TEMPLATEPATH . '/style.css');
        ?>

        <style>
            .update-nag {display: none;}
            #instructions {max-width: 800px;}
            h3.title {margin: 30px 0 0 0; padding: 30px 0 0 0; border-top: 1px solid #ddd;}
        </style>

        <div class="wrap">

            <div id="icon-tools" class="icon32"></div>
            <h2><?php echo $theme_data['Name'] . Translate::translate('Theme Updates'); ?></h2>
            <div id="message" class="updated below-h2">
                <p><strong>
                <?php 
                echo Translate::params('There is a new version of the %s theme available', $theme_data['Name']);
                ?>
                .</strong> You have version 
                <?php echo $theme_data['Version']; ?> 
                installed. Update to version 
                <?php echo $xml->latest; ?>
                .</p></div>

            <img style="float: left; margin: 0 20px 20px 0; border: 1px solid #ddd;" src="<?php echo get_template_directory_uri() . '/screenshot.png'; ?>" />

            <div id="instructions" style="max-width: 800px;">
                <h3><?php echo Translate::translate('Update Download and Instructions'); ?></h3>
                <p> 
                    <?php echo Translate::params('<strong>Please note:</strong> make a <strong> backup</strong> of the Theme inside your WordPress installation folder<strong> /wp-content/themes/%s/</strong>', sanitize_title($theme_data['Name']));?> </p>
                <p>
                    <?php echo Translate::translate('To update the Theme, login to your account, head over to your <strong>downloads</strong> section and re-download the theme like you did when you bought it.');?>
                </p>
                <p>
                    <?php echo Translate::params('Extract the zip\'s contents, look for the extracted theme folder, and after you have all the new files upload them using FTP to the <strong>/wp-content/themes/%s/</strong> folder overwriting the old ones (this is why it\'s important to backup any changes you\'ve made to the theme files)', sanitize_title($theme_data['Name']));?>.
                </p>
                <p>
                    <?php echo Translate::translate('If you didn\'t make any changes to the theme files, you are free to overwrite them with the new ones without the risk of losing theme settings, pages, posts, etc, and backwards compatibility is guaranteed');?>.
                </p>
            </div>

            <div class="clear"></div>

            <h3 class="title"><?php echo Translate::translate('Changelog');?></h3>
            <?php echo $xml->changelog; ?>
        </div>
        <?php
    }
    /**
     * This function retrieves a remote xml
     * file on my server to see if there's a new update
     * For performance reasons this function caches the
     * xml content in the database for XX seconds ($interval variable)
     *
     * @param int $interval time in secound
     * @return object simple xml object
     */
    private function getLatestThemeVersion($interval)
    {
        // remote xml file location
        $db_cache_field = THEME_CONTEXT . '-notifier-cache';
        $db_cache_field_last_updated = THEME_CONTEXT . '-notifier-last-updated';
        $last = get_option($db_cache_field_last_updated);
        $now = time();
        // check the cache
        if (!$last || (($now - $last) > $interval)) {
            $notfier_xml_file = $this->getNotifierXmlFile();
            // cache doesn't exist, or is old, so refresh it
            if (function_exists('curl_init')) { // if cURL is available, use it...
                $ch = curl_init($notfier_xml_file);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $cache = curl_exec($ch);
                curl_close($ch);
            } else {
                $cache = file_get_contents($notfier_xml_file); // ...if not, use the common file_get_contents()
            }

            if ($cache) {
                // we got good results
                update_option($db_cache_field, $cache);
                update_option($db_cache_field_last_updated, $now);
            }
            // read from the cache file
            $notifier_data = get_option($db_cache_field);
        } else {
            // cache file is fresh enough, so read from it
            $notifier_data = get_option($db_cache_field);
        }

        if (Helper\Xml::is_valid_xml($notifier_data)) {
            return simplexml_load_string($notifier_data);
        } else {
            return false;
        }

    }
}
