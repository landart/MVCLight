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
 * @category	Controllers
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.1a
 * @since		Version 0.1a
 */

/**
 * system/lib/errorController.class.php
 * 
 * System Default error Controller, to show 404 messages
 * 
 * @category	Controllers
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.1a
 * @since		Version 0.1a
 */
Class errorController Extends appController 
{
	/**
	 * Alias for notFound (404)
	 *  
	 */
	public function index()
	{
		$this->notFound();
	}
	
	/**
	 * Show a 404 error
	 *  tries to locate a view file, otherwise prints a custom message 
	 *  
	 */
	public function notFound()
	{
		$view = 'error/404';
		$file = __APP_PATH . '/views/' . $view . '.php';

		if (file_exists($file))
		{
			$this->template->show($view);
			
			return;
		}
		
		echo 'Ooops!! This route has not been declared yet. You should create a controller named ' . $this->registry->router->getController() . ' with an action called ' . $this->registry->router->getAction();
		echo ' or at least create a 404 view on ' . __APP_PATH . '/views/' . $view . '.php';
	}
	
	/**
	 * Shows a 503 error
	 *  tries to use a view file, otherwise prints a custom message
	 * 
	 */
	public function notAllowed()
	{
		$view = 'error/notAllowed';
		$file = __APP_PATH . '/views/' . $view . '.php';

		if (file_exists($file))
		{
			$this->template->show($view);
			
			return;
		}
		
		echo 'Howdy Cowboy!! It seems like you don\'t have access to this resource... You should check access permissions for your user role to ' . $this->registry->router->getController() . '/' . $this->registry->router->getAction();
		echo ' or at least create a notAllowed view on ' . __APP_PATH . '/views/' . $view . '.php to customize this message';
	}
}
?>
