<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/File.php $
  Last committed: $Revision: 2501 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-02-27 18:16:13 +0100 (Pt, 27 lut 2015) $
  ID: $Id: File.php 2501 2015-02-27 17:16:13Z padalec $
 */

namespace SilverWp\File;

/**
 * File operation
 *
 * @author        Michal Kalkowski <michal at silversite.pl>
 * @version       0.3
 * @category      WordPress
 * @package       File
 * @copyright     2009 - 2015 (c), SilverSite.pl
 */
class File {

	/**
	 *
	 * Get list of files from directory
	 *
	 * @param string $path             - path to list files
	 * @param array  $exclude_files    - files name exclude from list
	 * @param bool   $remove_extension - if true remove extension from file
	 *
	 * @param bool   $full_path
	 *
	 * @return array array with file list
	 * @static
	 */
	public static function getFileListFromDir(
		$path,
		$exclude_files = array(),
		$remove_extension = false,
		$full_path = false
	) {
		$file_path  = '';
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
						$file_path = $file_info->getPath() . '/';
					}
					if ( $remove_extension ) {
						$file_name = self::removeExtension( $path . '/' . $file_name );
					}
					$files[] = $file_path . $file_name;
				}
			}
		}

		return $files;
	}

	/**
	 *
	 * Remove extension from file name
	 *
	 * @param string $path      absolute path to file
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
	 * @param string $file_path full pat to file (including file name)
	 *
	 * @param string $extension file extension default php
	 *
	 * @return bool
	 * @throws FileException
	 * @throws IOException
	 * @static
	 * @access public
	 */
	public static function exists( $file_path, $extension = 'php' ) {
		$file = $file_path . '.' . $extension;
		if ( ! file_exists( $file ) ) {
			throw new FileException( "File $file does not exists!" );
		}

		if ( ! is_readable( $file ) ) {
			throw new IOException( "File $file is not readable!" );
		}

		$fp = fopen( $file, 'r', true );
		if ( is_resource( $fp ) ) {
			fclose( $fp );

			return true;
		} else {
			throw new FileException( "File $file cannot be opened!" );
		}

	}

}
