<?php
/**
 * Basic Exception
 *
 * @author oncletom
 * @package wp-scss
 * @subpackage lib
 */
class WPScssException extends Exception
{
  /**
   * Override the display output of the exception for WordPress
   *
   * @author oncletom
   * @see Exception::__toString()
   */
  public function __toString()
  {
    wp_die($this->getMessage().'<br /><pre>'.$this->getTraceAsString().'</pre>', 'WP-SCSS exception');
  }
}
