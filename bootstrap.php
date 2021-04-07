<?php
use a11y_for_fwp\Plugin;
/**
 * Register Autoloader
 */
spl_autoload_register(function ($class) {
	$prefix = 'a11y_for_fwp';
	$base_dir = __DIR__ . '/classes/';
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
		return;
	}
	$relative_class = substr($class, $len);
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
	if (file_exists($file)) {
		require $file;
	}
});

/**
 * Boot it
 */
add_action( 'init', function(){
	new Plugin();
}, 1 );
