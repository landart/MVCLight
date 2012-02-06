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
 * @category	System
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.2a
 * @since		Version 0.1a
 */

/**
 * system/lib/router.class.php
 * 
 * The router class used to execute the desired controller/method
 * 
 * @category	System
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.1a
 * @since		Version 0.1a
 */
class router
{
	/**
	 * the controller path
	 * 
	 * @access private
	 */
	private $path;
	
	/**
	 * array of arguments
	 * 
	 * @access private
	 * 
	 */
	private $args = array();
	
	/**
	 * the file to load
	 * 
	 * @access private
	 * 
	 */
	private $file;
	
	/**
	 * the controller name to use
	 * 
	 * @access private
	 * 
	 */
	private $controllerName;
	
	/**
	 * A reference to the controller being executed
	 * 
	 * @access private
	 * 
	 */
	private $controller;
	
	/**
	 * the action to execute
	 * 
	 * @access private
	 * 
	 */
	private $action; 
	
	/**
     * Router object provides storage for shared objects.
     * 
     * @var Router
     * 
     */
    private static $singleton = null;
    
    /**
     * The initialization time, for profiling purposes
     * 
     * @access public
     * 
     */
    public $loadTime = null;
    
    /**
     * The execution starting time, for profiling purposes
     * 
     * @access public
     * 
     */
    public $executeTime = null;
    
    /**
     * Keeps a reference to registry
     * 
     * @access private
     */
    private $registry = null;
	
	/**
	 * Keeps a buffer of user defined routes
	 * 
	 * @var array
	 * @access private
	 */
	private $userDefinedRoutes = null;
	
	/**
	 * Constructs and loads router and controller path
	 * 
	 * @return void
	 * 
	 */
	private function __construct() 
	{
		$this->registry = Registry::getInstance();
		
		$this->setPath( __APP_PATH . '/controllers' );
		
		$this->loadUserDefinedRoutes();
		
		$this->parseRoute();
		
	    // if profiler is activated, load microtime
        if ($this->registry->get('enable_profiler'))
        {
        	Profiler::addLog('loading router');
        }
	}
	
	/**
	 * The main destructor erases singleton object
	 * 
	 * @return void
	 */
	public function __destruct() 
	{  			
      	self::$singleton = null ;
  	}
	
	/**
	 * __clone private so nobody can clone the instance
	 *
	 * @access private
	 */
	private function __clone(){}
	
	/**
     * Retrieves the default router instance.
     *
     * @return Router
     * 
     */
    public static function getInstance()
    {
        if (self::$singleton === null) 
        {
            self::$singleton = new Router();
        }

        return self::$singleton;
    }
	
	/**
	 *
	 * @set controller directory path
	 *
	 * @param string $path
	 *
	 * @return void
	 *
	 */
	private function setPath($path) 
	{
		// check if path is a directory
		if (is_dir($path) == false)
		{
			throw new Exception ('Invalid controller path: "' . $path . '"');
		}
		// set the path
	 	$this->path = $path;
	}
	
	/**
	 *
	 * @load the controller
	 *
	 * @access public
	 *
	 * @return void
	 *
	 */
	public function loader()
	{	
		// include the controller
		include $this->file;
	
		// a new controller class instance
		$class 				= $this->controllerName . 'Controller';
		$this->controller 	= new $class();
	
		// check if the action is callable
		if (is_callable(array($this->controller, $this->action)) == false)
		{
			//@TODO: redirect flow to error/notAllowed
			$this->action = 'index';
		}
			
		// if profiler is activated, show some debug info
        if ($this->registry->get('enable_profiler'))
        {
        	Profiler::addLog('launching action');
        	Profiler::checkpoint('total to launch action');
        	Profiler::setAction($this->action);
        	Profiler::setController($this->controllerName);
        }
		
		// run the action
		$action = $this->action;
		call_user_func_array(array($this->controller, $action), $this->args);
		
		// if profiler is activated, show some debug info
        if ($this->registry->get('enable_profiler'))
        {
        	//Profiler::checkpoint('executing action');
        	Profiler::finish('total execution time');
        	Profiler::show();
        }
        
	}
		
	/**
	 *
	 * @get the controller
	 *
	 * @access private
	 *
	 * @return void
	 *
	 */
	private function parseRoute() 
	{
		// get the route from the url
		$route = $this->registry->input->get('rt');
		if ( empty( $route ) )
		{
		 	$route = $this->registry->default_route;
		}	
		else
		{
			// is there any route specific information?
			$route = $this->checkUserDefinedRoutes($route);
		}	
		
		// get the parts of the route
		$parts = explode('/', $route);
		$this->controllerName = $parts[0];
		if(isset( $parts[1]))
		{
			$params = explode('?', $parts[1]);
			$this->action = $params[0];
		}
		
		// pass the rest of the route as arguments
		$this->args = array_slice($parts,2);
		
		// set the file path
		$this->file = $this->path .'/'. $this->controllerName . 'Controller.php';
		
		// custom error controller?
		if (!file_exists($this->file))
		{
			$this->controllerName = 'error';
			$this->action = 'index';
			
			$this->file = $this->path .'/'. $this->controllerName . 'Controller.php';
			
			if (!file_exists($this->file))
			{
				$this->file = __SYSTEM_PATH . '/lib/'. $this->controllerName . 'Controller.class.php';	
			}			
		}
		
		// if the file is not there, show error
		if (is_readable($this->file) == false)
		{
			$this->file 			= $this->path . '/errorController.php';
	        $this->controllerName 	= 'error';
		}
		
		return;
	}
	
	/**
	 * Loads the user defined routes from the config file
	 * 
	 * @return boolean 
	 */
	private function loadUserDefinedRoutes()
	{
		$file = __APP_PATH . '/config/routes.ini.php';

		if ( is_readable( $file ) )
		{
			include($file);
			
			if (isset($routes))
			{
				$this->userDefinedRoutes = $routes;
			}
			
			return true;	
		}
		
		return false;		 
	}
	
	/**
	 * Checks the requested route to determine if it has to be replaced
	 *  with a user defined option
	 *  
	 * @param object $route [optional]
	 * @return 
	 */
	private function checkUserDefinedRoutes( $route = '')
	{
		$returnController 	= '';
		$returnAction 		= '';
		$foundController	= false;
		$foundAction		= false;
		$reqController 		= '';
		$reqAction 			= '';
		$tail 				= '';
		$userController 	= '';
		$userAction 		= '';
		$mapController  	= '';
		$mapAction 			= '';
		$r					= null;
		$v 					= null;
		
		list ( $reqController, $reqAction, $tail ) = $this->parseRouteToArray($route);
		
		$returnController 	= $reqController;
		$returnAction 		= $reqAction;
		
		foreach ( $this->userDefinedRoutes as $r=>$v )
		{
			list ( $userController, $userAction ) = $this->parseRouteToArray($r);
			list ( $mapController, $mapAction ) = $this->parseRouteToArray($v);
				
			// controller mapping
			if ($reqController == $userController)
			{
				$returnController 	= $mapController;
				$foundController 	= true;
				
				if ( $reqAction == $userAction || 
				 	 ( $userAction == '#' ) )
				{
					$returnAction 	= $mapAction;
					$foundAction	= true;
				}
			}
			// method mapping
			else if ($userController == '')
			{
				$foundController = true;
				if ( $reqAction == $userAction )
				{
					$returnAction 	= $mapAction;
					$foundAction	= true;
				}
			}
		
			if ($foundController && $foundAction)
			{
				break;
			}
		}
		
		if ( ( $userAction == '#' ) && $foundController && $foundAction)
		{
			$tail = $reqAction;
		}
	
		return $returnController . '/' . $returnAction . '/' . $tail;
	}
	
	/**
	 * Gets controller and action from a route string
	 * 
	 * @param string route chain
	 * 
	 * @return array of controller and action 
	 */
	private function parseRouteToArray( $route = '' )
	{
		$tmp 		= explode('/', $route, 3);
		$controller = '';
		$action 	= '';
		
		if (sizeof($tmp) == 0)
		{
			return $route;
		}
		
		if ( isset($tmp[0]) )
		{
			$controller = $tmp[0];
		}
		
		if ( isset($tmp[1]) )
		{
			$action = $tmp[1];
		}
		
		if ( ! isset($tmp[2]) )
		{
			$tmp[2] = '';
		}
		
		return array($controller, $action, $tmp[2]);
	}
	
	/**
	 * Basic getter for $this->action
	 * 
	 * @return string $this->action
	 * 
	 * @access public
	 * 
	 */
	public function getAction()
	{
		return $this->action;
	}
	
	/**
	 * Basic getter for $this->controllerName
	 * 
	 * @return string $this->controllerName
	 * 
	 * @access public
	 * 
	 */
	public function getController()
	{
		return $this->controllerName;
	}
}

?>