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
 * @category	Views
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.2a
 * @since		Version 0.1a
 */

/**
 * system/lib/template.class.php
 * 
 * The template class manages the views loading
 * 
 * @category	Views
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.1a
 * @since		Version 0.1a
 */
Class Template
{
	/**
	 * StdClass $this reference
	 * 
	 * @var Template
	 * @access private 
	 */
	private static $singleton = null;
	
	/**
	 * Registry reference
	 * 
	 * @var Registry
	 * @access private
	 */
	private $registry 		= null;
	
	/**
	 * Session reference
	 * 
	 * @var Session
	 * @access private
	 */
	private $session 		= null;
	
	/**
	 * Variables array
	 * 
	 * @var array
	 * @access private 
	 */
	private $vars 			= array();
	
	/**
	 * The name of the controller in use
	 * 
	 * @var string
	 * @access private
	 */
	private $controller 	= '';
	
	/**
	 * The name of the action executed
	 * 
	 * @var string
	 * @access private
	 */
	private $action 		= '';
	
	/**
	 * Layout used to render the views
	 * 
	 * @var string
	 * @access private
	 */
	private $layout 		= '';
	
	/**
	 * Title used to label the page
	 * 
	 * @var string
	 * @access private
	 */
	private $title 			= '';
	
	/**
	 * Description used to label the page
	 * 
	 * @var string
	 * @access private
	 */
	private $description 	= '';
	
	/**
	 * Keywords used to label the page
	 * 
	 * @var string
	 * @access private
	 */
	private $keywords 		= '';
	
	/**
	 * Default site charset
	 * 
	 * @var string
	 * @access private
	 */
	private $charset		= '';
	
	/**
	 * Particular CSS style to apply to the page
	 * 
	 * @var string
	 * @access private
	 */
	private $css 			= '';
	
	/**
	 * Particular JS file to load with the page
	 * 
	 * @var string
	 * @access private
	 */
	private $js 			= '';	
	
	/**
	 * A flash message with the result of the last action
	 * 
	 * @var string
	 * @access private
	 */
	private $flash_message 	= '';
	
	/**
	 * Constructor, we avoid external instantiation of this class
	 * 
	 * @return void
	 */
	private function __construct()
	{
		// registry
		$this->registry		= Registry::getInstance();
		
		// session is loaded before registry in input.php
		// therefore, it can be referenced here for simplicity
		$this->session		= $this->registry->session;
						
		// controller name
		$this->controller 	= $this->registry->router->getController();
		
		// action name
		$this->action 		= $this->registry->router->getAction();
		
		// flash message
		$this->flash_message = $this->session->getFlash('message');
		
		// finally load some defaults from config
		$this->layout 		= $this->registry->get('page_layout');
		$this->title 		= $this->registry->get('page_title');
		$this->description 	= $this->registry->get('page_description');
		$this->keywords 	= $this->registry->get('page_keywords');
		$this->charset 		= $this->registry->get('charset');
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
	 *
	 * Return Template instanc
	 * 
	 * @return Template object
	 *
	 * @access public
	 */
	public static function getInstance() 
	{
		if (!self::$singleton) 
		{
	   		self::$singleton = new Template();
		}
		
		return self::$singleton;
	}
	
	/**
	 *
	 * @set undefined vars
	 *
	 * @param string $index
	 *
	 * @param mixed $value
	 *
	 * @return void
	 *
	 */
	 public function set($index, $value)
	 {
        $this->vars[$index] = $value;
	 }
	 
	 /**
	  * Allows to set the default layout to be used
	  * 
	  * @param string $layout
	  * 
	  * @access public
	  * 
	  */
	 public function layout($layout = '')
	 {
	 	$path = __APP_PATH . '/views/' . $layout . '.php';
		
		if (file_exists($path))
		{
			$this->layout = $layout;	
		}
	 	
	 }
	
	/**
	 * Renders the selected view
	 * 
	 * @param string $name of the view
	 * @param mixed $vars optional array of variables to set for the view
 	 * @param boolean $return whether to return the content instead of showing it
 	 * 
 	 * @important!!! this method is overloaded so that third parameter can be passed in second position;
 	 * 					Then, to force return of output without passing parameters, use show('view',true);
 	 * 					Force return is disables by default and variables are null also by default
	 * 
	 * @return string the rendered view code
	 * 
	 */	 
	public function show($name, $vars = array(), $return = false) 
	{
		$path 	= __APP_PATH . '/views' . '/' . $name . '.php';
		$buffer = '';
		$start	= microtime(true);
		
		if (file_exists($path) == false)
		{
			$exception = new Exception();
			if ($this->registry->get('enable_profiler'))
			{
				Profiler::error('Template not found in '. $path . ' >> ' . $exception);
			}

			return false;
		}
		
		// prefilter (third parameter might have been passed in second position)
		if ($vars === true || $vars === false)
		{
			$return = $vars;
			$vars 	= array();
		}		
		
		// vars always an array
		if (!is_array($vars))
		{
			$vars = array();
		}
		
		// set input variables, if any
		foreach ($vars as $key => $value)
		{
			$this->set($key,$value);
		}
	
		// Load variables
		foreach ($this->vars as $key => $value)
		{
			$$key = $value;
		}
		
		// start output buffering
	    ob_start();
			
		include ($path);
		
		$html = ob_get_clean();
		//
				
		$html = reduce($html);
		
		if ($this->registry->get('enable_profiler'))
		{
			if (Profiler::$firstView)
			{ 
				Profiler::$firstView = false;
				Profiler::checkpoint('Total to execute queries');
			}
			Profiler::addLog('Loading view ' . $name);	
		}
		
		if ($return)
		{
			return $html;
		}
		
		// otherwise
		echo $html;	
		          
	}

	/**
	 * Renders the selected block of view and returns the output
	 * Alias for $this->show('name','vars',true);
	 * 
	 * @param string $name of the view
	 * @param mixed $vars optional array of variables to set for the view
	 * 
	 * @return string the rendered view code
	 * 
	 * @access public
	 *  
	 */	 
	public function render($view = '', $params = array())
	{	
		return $this->show( $view, $params, true);
	}
	
	/**
	 * Renders a whole page by inserting the content into the layout
	 * 
	 * @param string $view the view to render
	 * 
	 * @param mixed array of parameters for the view to process
	 * 
	 * @param mixed array of settings for the page to process
	 * @param string $settings['title'] 
	 * @param string $settings['description']
	 * @param string $settings['keywords']
	 * @param string $settings['css']
	 * @param string $settings['js']
	 * 
	 * @access public
	 */
	public function page($view = '', $params = array(), $settings = array())
	{
		// overwrite attributes with settings, if available
		$this->title 		= isset( $settings['title'] ) ? $settings['title'] : $this->title;
		$this->description	= isset( $settings['description'] ) ? $settings['description'] : $this->description;
		$this->keywords		= isset( $settings['keywords'] ) ? $settings['keywords'] : $this->keywords;
		$this->css			= isset( $settings['css'] ) ? $settings['css'] : $this->css;
		$this->js			= isset( $settings['js'] ) ? $settings['js'] : $this->js;
		
		$content 			= $this->render($view, $params); // pregenerate the content
		
		echo $this->render($this->layout,array('content_for_layout'=>$content));
		
		if ($this->registry->get('enable_profiler'))
		{
			Profiler::checkpoint('Total to load views');
		}
	}
}

?>
