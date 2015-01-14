<?php

	/** application root directory */
	if (!defined('WEBAPP_ROOT')) {
		define('WEBAPP', dirname(__FILE__) . '/');
		require(WEBAPP . 'webapp/Autoloader.php');
	}
	
	/**
	* WebApp
	*
	* @copyright  Copyright (c) 2014 Matt Bullard
	*/

	class WebApp
	{

		private $_properties;
		public $prop1 = "Property";
		
		public function __construct()
		{
			// Create application properties
			$this->_properties = new WebApp_AppProperties();
		}

		public function __destruct()
		{
			//echo 'The class "', __CLASS__, '" was destroyed.<br />';
		}

		public function __toString()
		{
			return $this->getProperty();
		}
		
		public function getProperty()
		{
			return $this->prop1 . "<br />";
		}

		public function setProperty($newval)
		{
			$this->prop1 = $newval;
		}		
		
	}

?>
