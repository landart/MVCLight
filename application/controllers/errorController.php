<?php
/**
 * Manifest
 * 
 * application/controllers/errorController.php
 * 
 * Application error Controller, to show 404 messages
 * 
 * @copyright: Jorge Albaladejo [http://jorgealbaladejo.com].
 * 
 */

/**
 * application/controllers/errorController.php
 * 
 * Manages application errors, currently 404 messages
 * 
 */
Class errorController Extends appController 
{
	public function index()
	{
		$view = 'error/404';
		$file = __APP_PATH . '/views/' . $view . '.php';
	
		if (file_exists($file))
		{
			$this->template->page($view);
			
			return;
		}
		
		echo 'Ooops!! This route has not been declared yet. You should create a controller named ' . $this->registry->router->getController() . ' with an action called ' . $this->registry->router->getAction();
		echo ' or at least create a 404 view on ' . __APP_PATH . '/views/' . $view . '.php';
	}
}
?>
