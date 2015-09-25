<?php
/**
 * SCSS compiler
 *
 * @author oncletom
 * @extends scssc
 * @package wp-scss
 * @subpackage lib
 * @since 1.2
 * @version 1.3
 */
class WPScssCompiler extends \Leafo\ScssPhp\Compiler
{
	/**
	 * Instantiate a compiler
	 *
   	 * @api
	 * @see	scssc::__construct
	 * @param $file	string [optional]	Additional file to parse
	 */
	public function __construct($file = null)
	{
  	do_action('wp-scss_compiler_construct_pre', $this, $file);
	}

	/**
   	* Registers a set of functions
   	*
   	* @param array $functions
   	*/
  	public function registerFunctions(array $functions = array())
  	{
  		foreach ($functions as $name => $args)
  		{
			$this->registerFunction($name, $args['callback']);
		}
  	}

	/**
	 * Returns available variables
	 *
	 * @since 1.5
	 * @return array Already defined variables
	 */
	public function getVariables()
	{
		return $this->registeredVars;
	}

	public function setVariable($name, $value)
	{
		$this->registeredVars[ $name ] = $value;
	}

	public function getImportDir()
	{
		return (array)$this->importDir;
	}

	/**
	 * Smart caching and retrieval of a tree of @import SCSS stylesheets
	 *
	 * @since 1.5
	 * @param WPScssStylesheet $stylesheet
	 * @param bool $force
	 */
	public function cacheStylesheet(WPScssStylesheet $stylesheet, $force = false)
	{
		$cache_name = 'wp_scss_compiled_'.md5($stylesheet->getSourcePath());
		$compiled_cache = get_transient($cache_name);

		if( !$force && !file_exists( $stylesheet->getTargetPath() ) ) $force = true;

		$compiled_cache = $this->cachedCompile($compiled_cache ? $compiled_cache : $stylesheet->getSourcePath(), $force);

		// saving compiled stuff
		if (isset($compiled_cache['compiled']) && $compiled_cache['compiled'])
		{
      			$stylesheet->setSourceTimestamp($compiled_cache['updated']);
			$this->saveStylesheet($stylesheet, $compiled_cache['compiled']);

			$compiled_cache['compiled'] = NULL;
			set_transient($cache_name, $compiled_cache);
		}
	}

	/**
	 * Process a WPScssStylesheet
	 *
	 * This logic was previously held in WPScssStylesheet::save()
	 *
	 * @since 1.4.2
	 * @param WPScssStylesheet $stylesheet
	 * @param null $css
	 */
	public function saveStylesheet(WPScssStylesheet $stylesheet, $css = null)
	{
		wp_mkdir_p(dirname($stylesheet->getTargetPath()));

		try
		{
			do_action('wp-scss_stylesheet_save_pre', $stylesheet, $this->getVariables());

			if ($css === null)
			{
				$css = $this->compile(file_get_contents($stylesheet->getSourcePath()));
			}

			file_put_contents($stylesheet->getTargetPath(), apply_filters('wp-scss_stylesheet_save', $css, $stylesheet));
			//chmod($stylesheet->getTargetPath(), 0666);

			$stylesheet->save();
			do_action('wp-scss_stylesheet_save_post', $stylesheet);
		}
		catch(Exception $e)
		{
			wp_die($e->getMessage());
		}
	}
}
