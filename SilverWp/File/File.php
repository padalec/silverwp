<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Helper/File.php $
  Last committed: $Revision: 2501 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-02-27 18:16:13 +0100 (Pt, 27 lut 2015) $
  ID: $Id: File.php 2501 2015-02-27 17:16:13Z padalec $
 */

namespace SilverWp\File;
use SilverWp\Debug;

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
			throw new FileException( 'File $file does not exists!' );
		}

		if ( ! is_readable( $file ) ) {
			throw new IOException( 'File $file is not readable!' );
		}

		$fp = fopen( $file, 'r', true );
		if ( is_resource( $fp ) ) {
			fclose( $fp );

			return true;
		} else {
			throw new FileException( 'File $file cannot be opened!' );
		}

	}

	/**
	 * Check the file exists from current URL
	 *
	 * @param string $url
	 *
	 * @return bool
	 * @access public
	 * @static
	 */
	public static function isExistUrl( $url ) {
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_NOBODY, true );
		curl_exec( $ch );
		$code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

		if ( $code == 200 ) {
			$status = true;
		} else {
			$status = false;
		}
		curl_close( $ch );

		return $status;
	}

	public static function download( $file ) {

		//- turn off compression on the server
		if ( function_exists( 'apache_setenv' ) ) {
			apache_setenv( 'no-gzip', 1 );
		}
		//ini_set( 'zlib.output_compression', 'Off' );

		// sanitize the file request, keep just the name and extension
		// also, replaces the file location with a preset one ('./myfiles/' in this example)
		$file_path  = $file;
		$path_parts = pathinfo( $file_path );
		$file_name  = $path_parts['basename'];
		$file_ext   = $path_parts['extension'];
		//$file_path  = './myfiles/' . $file_name;
		// allow a file to be streamed instead of sent as an attachment
		$is_attachment = isset( $_REQUEST['stream'] ) ? false : true;

		// make sure the file exists
		if ( is_file( $file_path ) ) {
			$file_size = filesize( $file_path );
			$file      = fopen( $file_path, 'rb' );
			if ( $file ) {
				// set the headers, prevent caching
				header( 'Pragma: public' );
				header( 'Expires: -1' );
				header( 'Cache-Control: public, must-revalidate, post-check=0, pre-check=0' );
				header( 'Content-Disposition: attachment; filename="'.$file_name.'"' );

				// set appropriate headers for attachment or streamed file
				if ( $is_attachment ) {
					header( 'Content-Disposition: attachment; filename="'. $file_name.'"' );
				} else {
					header( 'Content-Disposition: inline;' );
				}

				// set the mime type based on extension, add yours if needed.

				$ctype_default = 'application/octet-stream';
				$content_types = array(
					'exe' => 'application/octet-stream',
					'zip' => 'application/zip',
					'mp3' => 'audio/mpeg',
					'mpg' => 'video/mpeg',
					'avi' => 'video/x-msvideo',
				);
				$ctype = isset( $content_types[ $file_ext ] ) ? $content_types[ $file_ext ] : $ctype_default;
				header( 'Content-Type: ' . $ctype );

				//check if http_range is sent by browser (or download manager)
				if ( isset( $_SERVER['HTTP_RANGE'] ) ) {
					list( $size_unit, $range_orig ) = explode( '=', $_SERVER['HTTP_RANGE'], 2 );
					if ( $size_unit == 'bytes' ) {
						//multiple ranges could be specified at the same time, but for simplicity only serve the first range
						//http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
						list( $range, $extra_ranges ) = explode( ',', $range_orig, 2 );
					} else {
						header( 'HTTP/1.1 416 Requested Range Not Satisfiable' );
						exit;
					}
				} else {
					$range = '';
				}

				//figure out download piece from range (if set)
				list( $seek_start, $seek_end ) = explode( '-', $range, 2 );

				//set start and end based on range (if set), else set defaults
				//also check for invalid ranges.
				$seek_end   = ( empty( $seek_end ) ) ? ( $file_size - 1 )
					: min( abs( intval( $seek_end ) ), ( $file_size - 1 ) );
				$seek_start = ( empty( $seek_start ) || $seek_end < abs( intval( $seek_start ) ) )
					? 0 : max( abs( intval( $seek_start ) ), 0 );

				//Only send partial content header if downloading a piece of the file (IE workaround)
				if ( $seek_start > 0 || $seek_end < ( $file_size - 1 ) ) {
					header( 'HTTP/1.1 206 Partial Content' );
					header( 'Content-Range: bytes ' . $seek_start . '-' . $seek_end . '/' . $file_size );
					header( 'Content-Length: ' . ( $seek_end - $seek_start + 1 ) );
				} else {
					header( 'Content-Length: '. $file_size );
				}

				header( 'Accept-Ranges: bytes' );

				set_time_limit( 0 );
				fseek( $file, $seek_start );

				while ( ! feof( $file ) ) {
					print( fread( $file, 1024 * 8 ) );
					ob_flush();
					flush();
					if ( connection_status() != 0 ) {
						fclose( $file );
						exit;
					}
				}

				// file save was a success
				fclose( $file );
				exit;
			} else {
				// file couldn't be opened
				header( 'HTTP/1.0 500 Internal Server Error' );
				exit;
			}
		} else {
			// file does not exist
			header( 'HTTP/1.0 404 Not Found' );
			exit;
		}
	}
}
