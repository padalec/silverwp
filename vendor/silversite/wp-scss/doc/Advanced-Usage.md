# Advanced Usage

This part of the documentation will help you adapting `wp-scss` plugin to your very own needs without breaking anything.

## Registering a SCSS variable

You can inject [SCSS variables](http://leafo.net/scssphp/docs/#variables) before the compilation process. You can then use **dynamic variables** defined upon WordPress settings or your own business logic.

This can be performed with 2 methods of the `WPScssPlugin` class:
* `addVariable($name string, $value string|number)`: sets 1 SCSS variable value;
* `setVariables($variables array)`: sets several SCSS variables value at one.

```php
// wp-content/themes/your-theme/functions.php

if (class_exists('WPScssPlugin')){
	$scss = WPScssPlugin::getInstance();

	$scss->addVariable('myColor', '#666');
	// you can now use @myColor in your *.scss files

	$scss->setVariables(array(
		'myColor' => '#777',
		'minSize' => '18px'
	));
	// you can now use @minSize in your *.scss files
	// @myColor value has been updated to #777
}
```

## Registering a SCSS function

You can inject [custom SCSS functions](http://leafo.net/scssphp/docs/#custom_functions) before the compilation process. You can now package SCSS helpers for your theme in a very useable way (even as WordPress plugins).

This can be performed with 1 method of the `WPScssPlugin` class:
* `registerFunction($name string, $callback string)`: binds a PHP callback function to a SCSS function.

```php
// wp-content/themes/your-theme/functions.php

if (class_exists('WPScssPlugin')){
	$scss = WPScssPlugin::getInstance();

	function scss_generate_random($max = 1000){
		return rand(1, $max);
	}

	$scss->registerFunction('random', 'scss_generate_random');
	// you can now use random() in your *.scss files, like
	// div.random-size{
	// 	width: scss_generate_random(666);
	// }
}
```

**Notice**: don't forget the handy [native SCSS functions](http://leafo.net/scssphp/docs/#built_in_functions).

## Changing compilation target directory

By default `wp-scss` will outputs compiled CSS to your WordPress upload folder (by default: `wp-content/uploads/wp-scss`).
It’s done this way because this folder is usually available in *write mode*, even with tricky filesystem permissions.

You can alter the compile path both for filesystem and URIs. It is usefull if you have a CDN for theme assets or if your browser path is different than the filesystem one.

This can be performed with 2 methods of the `WPScssConfiguration` class:
* `setUploadDir($dir string)`: sets the new compile filesystem directory;
* `setUploadUrl($url string)`: sets several SCSS variables value at one.

```php
// wp-content/themes/your-theme/functions.php

if (class_exists('WPScssPlugin')){
	$scssConfig = WPScssPlugin::getInstance()->getConfiguration();

	// compiles in the active theme, in a ‘compiled-css’ subfolder
	$scssConfig->setUploadDir(get_stylesheet_directory() . '/compiled-css');
	$scssConfig->setUploadUrl(get_stylesheet_directory_uri() . '/compiled-css');
}
```
## Changing the scss compiler

By default `wp-scss` will use the compiler from the [leafo/scssphp](https://github.com/leafo/scssphp) library. wp-scss also ships with the [oyejorge/scss.php](https://github.com/oyejorge/scss.php) library, which is more up-to-date and contains the ":extend" scss language construct (which is needed for the compilation of certain frameworks including the latest Twitter Bootstrap).

__Note__ The `scss.php` library does not support registering custom php functions.

### Example using the scss.php library
```php
// wp-content/themes/your-theme/functions.php

if (class_exists('WPScssPlugin')){
	function my_theme_wp_scss_compiler()
	{
		return 'scss.php';
	}
	add_filter('wp_scss_compiler', 'my_theme_wp_scss_compiler');
}
```
