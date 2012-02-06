<?php
/**
 * MVCLight
 *
 * An open source application development framework for PHP
 * 
 * PHP version 5
 * 
 * LICENSE: This source file is subject to the license that is available
 * at the following url:
 * http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * If you did not receive a copy of the license and are unable to obtain
 * it through the web, please send a note to correo@jorgealbaladejo.com
 * so we can mail you a copy immediately.
 *
 * @category	Runtime
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.2a
 * @since		Version 0.1a
 */

/**
 * fw.php
 * 
 * Framework entry point.
 *  Defines paths
 *  Initializes the framework
 *  Excutes selected controller/method
 * 
 * @category	Runtime
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.2a
 * @since		Version 0.1a
 */

 // for profiling and debugging purposes
 define('TIME_START',microtime(true)); 
 
 // affects some debugging and error options
 define('PRODUCTION_SERVER',  $_SERVER['SERVER_NAME'] != 'localhost') ;
 
 // Initialization errors are critical and must be shown (only for development purposes)
  if (!PRODUCTION_SERVER)
 {
	ini_set('error_reporting',2147483647);
	ini_set('display_errors','on');
 }	
 else
 {
 	ini_set('error_reporting',0);
	ini_set('display_errors','off');
 }
 
 // define the site paths
 $site_path = realpath(dirname(__FILE__));
 define ('__SITE_PATH', $site_path);
 define ('__APP_PATH', $site_path . '/application');
 define ('__SYSTEM_PATH', $site_path . '/system');
 define ('__BASEURL', getBaseUrl());

 // Initialize the framework by including config and instancing objects
 include 'system/init.php';

 // load the controller
 $registry->router->loader();
 
 // end of execution
 exit(0);
 
 
 /*****************************************************************************************/
 /*********************************** System functions ************************************/
 /*****************************************************************************************/
 function __autoload($className)
 {
	// vars
	$end 	= null;
	$paths	= array();
	
	
	// should it be a model?
	if (strlen($className)>5)
	{
		$end	= substr($className,strlen($className)-5,strlen($className));
	}	
		
	// avoid lower case for the first letter
	$className 	= strtolower(substr($className,0,1)) . substr($className,1,strlen($className));
	
	// possible locations
	$paths[] 	= __APP_PATH . '/models/' . $className . '.php';
	$paths[]	= __APP_PATH . '/' . $className . '.php'; // appController and appModel
	$paths[] 	= __SYSTEM_PATH . '/lib/' . $className . '.class.php';
	$paths[] 	= __SYSTEM_PATH . '/vendor/' . $className . '.class.php';
	$paths[] 	= __APP_PATH . '/controllers/' . $className . '.php';
		
	foreach ( $paths as $path )
	{
		if ( file_exists($path) )
		{
			include $path;
			return true;
		}
	}
	
	// not found, halt
	throw new Exception('File class not found for '. $className);
	return false;
	
 }
 
 /**
  * Checks the web path to be used in view files
  * 	Uses $_SERVER['REQUEST_URI'] and $_SERVER['SCRIPT_NAME'] to match the common url
  *
  * @return string base web url 
  */
 function getBaseUrl()
 {
 	$request = explode('/',$_SERVER['REQUEST_URI']);
	$script  = explode('/',$_SERVER['SCRIPT_NAME']);
	$i		 = 0;
	$url	 = '';
	
	while (strtolower($request[$i]) == strtolower($script[$i]))
	{
		$url .= $request[$i] . '/';
		$i++;
	}
	
	return substr($url,0,strlen($url)-1);
 }
?>
