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
 * system/lib/baseObject.class.php
 * 
 * Abstract parent base object from which controllers and models inherit
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
Abstract Class baseObject 
{
	/**
	 * Object registry
	 * 
	 * @var Registry
	 * @access protected
	 */
	protected $registry;
	
	/**
	 * Object router
	 * 
	 * @var Router
	 * @access protected
	 */
	protected $router;
	
	/**
	 * Keeps a reference to the user session for further use
	 * 
	 * @var Session
	 * @access protected
	 */
	protected $session;
	
	/**
	 * Object template
	 * 
	 * @var Template
	 * @access protected
	 */
	protected $template;
	
	/**
	 * Object Input
	 * 
	 * @var Input
	 * @access protected
	 */
	 protected $input;
	 
	/**
	 * Object Language
	 * 
	 * @var Language
	 * @access protected
	 */
	 protected $language;
	 
	 /**
	  * Keeps a list of loaded models
	  * 
	  * @var array
	  * @access protected
	  */
	 protected $modelsBuffer;
	 
	 /**
	  * Keeps a list of loaded helpers
	  * 
	  * @var array
	  * @access protected
	  */
	 protected $helpersBuffer;
	
	/**
	 * Default constructor, loads the registry (singleton pattern)
	 * 
	 */
	public function __construct() 
	{
		$this->registry = Registry::getInstance();
		$this->router	= $this->registry->router;
		$this->template	= $this->registry->template;
		$this->session 	= $this->registry->session;
		$this->input	= $this->registry->input;
		$this->language	= $this->registry->language;	
		
		$this->modelsBuffer  = array();
		$this->helpersBuffer = array();
	}
	
	/**
	 * Loads a model from application or system folders
	 * 
	 * @param string $model name
	 * 
	 * Adds the model as an attribute of $this
	 * 
	 */
	public function model($model)
	{
		if (!is_array($this->modelsBuffer))
		{
			$this->modelsBuffer = array();
		}
		
		if (in_array($model,$this->modelsBuffer))
		{
			return;	
		}
		
		$file 	 = __APP_PATH . '/models/' . $model . 'Model.php';
		$adapter = __APP_PATH . '/adapters/' . $model . 'Adapter.php';
		
		if (!is_readable($file))
		{
			$file 	 = __SYSTEM_PATH . '/lib/models/' . $model . 'Model.php';
		}
		
		if (!is_readable($adapter))
		{
			$adapter = __SYSTEM_PATH . '/lib/adapters/' . $model . 'Adapter.php';
		}
		
		if (is_readable($file))
		{
			include_once ($file);
			
			$this->modelsBuffer[] = $model;
			
			// include adapter if exists
			if (is_readable($adapter))
			{
				include_once ($adapter);				
			}
			
			$modelName = $model . 'Model';
			$this->$model = new $modelName;
		}
		
	}
	
 	/**
	 * Loads a helper from application or system folders
	 * 
	 * @param string $helper name
	 * 
	 * Just loads the file and the functions are available
	 * 
	 */
	public function helper($helper)
	{
		if (in_array($helper,$this->helpersBuffer))
		{
			return;	
		}
		
		$file = __APP_PATH . '/helpers/' . $helper . '.php';
		
		if (!is_readable($file))
		{
			$file = __SYSTEM_PATH . '/helpers/' . $helper . '.php';
		}
		
		if (is_readable($file))
		{
			include ($file);
			
			$this->helpersBuffer[] = $helper;
		}
		
	}
	

}

?>
