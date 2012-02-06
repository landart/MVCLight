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
 * system/lib/baseController.class.php
 * 
 * Abstract parent base controller
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
Abstract Class baseController extends baseObject
{	
	/**
	 * Access array, configures ACL capabilities
	 * 
	 * @var array
	 */
	protected $access = null; 

	/**
	 * all controllers must contain an index method
	 * 
	 */
	abstract public function index();
	
	/**
	 * It keeps a record for further profiling
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// if profiler is activated, load microtime
        if ($this->registry->get('enable_profiler'))
        {
        	Profiler::addLog('loading controller');
        }
	}
	
	/**
	 * Uses every controller access array to determine user permissions.
	 * Checks if a user has access to a method on a controller.
	 * Takes user role from session.
	 * Takes method and controller from $this->router object.
	 *
	 * @return boolean true if access is granted
	 *
	 */
	protected function checkAccess()
	{
		// variables
		$method = $this->router->getAction();
		$role	= $this->session->getUserRole();
		//
		
		// we can override this feature to avoid using the ACL
		if ($this->access === null || !is_array($this->access))
		{
			return true;
		}
		
		// check access
		if ($method)
		{
			if (array_key_exists($method, $this->access))
			{
				// is role in access array?
				if (is_array($this->access[$method]))
				{
					if (in_array($role, $this->access[$method]))
					{
						return(true);
					}
				}
				else
				{	
					// is there global access?
					if (strtolower((string)$this->access[$method]) == 'all')
					{
						return(true);
					}
					// is there only one role? 
					if ((string)$this->access[$method] == $role)
					{
						return(true);
					}
				}
			}
			else
			{	
				redirect('error/notAllowed');
			}
		}
		return (false);		
	}
	
}

?>
