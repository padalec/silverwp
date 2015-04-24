<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/File.php $
  Last committed: $Revision: 2501 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-02-27 18:16:13 +0100 (Pt, 27 lut 2015) $
  ID: $Id: File.php 2501 2015-02-27 17:16:13Z padalec $
 */

namespace SilverWp\Helper;

/**
 * File operation class
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: File.php 2501 2015-02-27 17:16:13Z padalec $
 * @category WordPress
 * @package Helper
 * @copyright (c) 2009 - 2014, SilverSite.pl
 */
class File extends \RecursiveDirectoryIterator {

    /**
     *
     * get list of files from directory
     *
     * @param string $path - path to list files
     * @param array  $exclude_files - files name exclude from list
     * @param bool   $remove_extension - if true remove extension from file
     *
     * @return array array with file list
     */
    public static function get_file_list( $path, $exclude_files = array(), $remove_extension = false, $full_path = false ) {
        $file_path = '';
        $files      = array();
        $path       = \realpath( $path );
        $files_list = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator( $path ),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ( $files_list as $path => $file_info ) {
            if ( $file_info->isFile() ) {
                $file_name = $file_info->getFilename();
                if ( ! in_array( $file_name, $exclude_files ) ) {
                    if ( $full_path ) {
                        $file_path = $file_info->getPath() .'/';
                    }
                    if ( $remove_extension ) {
                        $file_name = self::removeExtension( $path . '/' . $file_name );
                    }
                    $files[ ] = $file_path . $file_name;
                }
            }
        }

        return $files;
    }

    /**
     *
     * Remove extension from file name
     *
     * @param string $path absolute path to file
     * @param string $extension file extension to remove
     *
     * @return string file without extension
     * @access private
     */
    private static function removeExtension( $path, $extension = '.php' ) {
        return basename( $path, $extension );
    }

    /**
     * Checks whether the file exists in the include path
     *
     * @param string $file_name file name
     *
     * @return bool
     */
    public static function exists( $file_name ) {
        if ( ! file_exists( $file_name ) ) {
            return false;
        }
        $fp = fopen( $file_name, 'r', true );
        if ( is_resource( $fp ) ) {
            fclose( $fp );

            return true;
        }

        return false;
    }

}
