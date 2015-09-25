<?php
/**
 * Creates easily a variable to be replaced on compilation
 *
 * @author oncletom
 * @since 1.4
 * @version 1.0
 * @param string $name
 * @param string $value
 * @return null
 */
function scss_add_variable($name, $value)
{
  WPPluginToolkitPlugin::getInstance('WPScss')->addVariable($name, $value);
}

/**
 * Creates easily a SCSS function to be replaced on compilation
 *
 * @author oncletom
 * @since 1.4.2
 * @version 1.0
 * @param string $name
 * @param string $callback
 * @return null
 */
function scss_register_function($name, $callback)
{
  WPPluginToolkitPlugin::getInstance('WPScss')->registerFunction($name, $callback);
}

/**
 * SCSSify a stylesheet on the fly
 *
 * <pre>
 * <head>
 *  <title><?php wp_title() ?></title>
 *  <link rel="stylesheet" media="all" type="text/css" href="<?php echo wp_scssify(get_bloginfo('template_dir').'/myfile.scss') ?>" />
 * </head>
 * </pre>
 *
 * @todo hook on WordPress cache system
 * @author oncletom
 * @since 1.2
 * @version 1.0
 * @param string $stylesheet_uri
 * @param string $cache_key
 * @param string $version_prefix
 * @return string processed URI
 */
function wp_scssify($stylesheet_uri, $cache_key = null, $version_prefix = '?ver=')
{
  static $wp_scss_uri_cache;
  $cache_key = 'wp-scss-'.($cache_key === '' ? md5($stylesheet_uri) : $cache_key);

  if (is_null($wp_scss_uri_cache))
  {
    $wp_scss_uri_cache = array();
  }

  if (isset($wp_scss_uri_cache[$cache_key]))
  {
    return $wp_scss_uri_cache[$cache_key];
  }

  /*
   * Register a fake stylesheet to make the process possible
   * It relies on a _WP_Dependency object
   */
  wp_register_style($cache_key, $stylesheet_uri);
  $stylesheet = WPScssPlugin::getInstance()->processStylesheet($cache_key);
  wp_deregister_style($cache_key);
  $wp_scss_uri_cache[$cache_key] = $stylesheet->getTargetUri();

  unset($stylesheet);
  return $wp_scss_uri_cache[$cache_key];
}

