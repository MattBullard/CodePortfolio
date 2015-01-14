<?php

WebApp_Autoloader::Register();

/**
 * Autoloader
 *
 * @copyright	Copyright (c) 2014
 */
class WebApp_Autoloader
{
	/**
	 * Register the Autoloader with SPL
	 *
	 */
	public static function Register() {
		if (function_exists('__autoload')) {
			//	Register any existing autoloader function with SPL, so we don't get any clashes
			spl_autoload_register('__autoload');
		}
		//	Register ourselves with SPL
		return spl_autoload_register(array('WebApp_Autoloader', 'Load'));
	}	//	function Register()


	/**
	 * Autoload a class identified by name
	 *
	 * @param	string	$pClassName		Name of the object to load
	 */
	public static function Load($pClassName){
		if ((class_exists($pClassName)) || (strpos($pClassName, 'WebApp') !== 0)) {
			return FALSE; // Either already loaded, or not a valid class request
		}

		$pObjectFilePath = WEBAPP . str_replace('_',DIRECTORY_SEPARATOR,$pClassName) . '.php';

		if ((file_exists($pObjectFilePath) === false) || (is_readable($pObjectFilePath) === false)) {
			return FALSE; // Can't load
		}

		require($pObjectFilePath);
	}	//	function Load()

}

?>
