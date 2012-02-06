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
 * system/init.php
 * 
 * Framework initialization
 * Loads the base files and creates a registry with all needed resources
 * 
 * @category	Runtime
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.1a
 * @since		Version 0.1a
 */
  
 // first of all, some helper functions
 include __SYSTEM_PATH . '/helpers/base.php';
 
 // a new registry object which loads application configuration
 $registry 	= Registry::getInstance();

 // assign common resources to registry object
 $registry->input		= Input::getInstance();
 $registry->cookie		= Cookie::getInstance();
 $registry->session 	= Session::getInstance();
 $registry->language	= Language::getInstance();
 
 // a new router object ready to execute the controller/method url
 $registry->router		= Router::getInstance();
 
 // templating system is slightly special
 $registry->template	= Template::getInstance();
 
 // database?
 if ($registry->db_auto_load)
 {
 	$registry->db		= Db::getInstance();
 }

?>
