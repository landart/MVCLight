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
 * @version		0.1a
 * @since		Version 0.1a
 */

/**
 * system/lib/registry.class.php
 * 
 * The application's registry, which keeps a status configuration
 *	And a reference to all common resources
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
Class Registry 
{
	/**
	 * @var array configuration
	 * 
	 * @access private
	 * 
	 */
 	private $config = array();
 	
    /**
     * Registry object provides storage for shared objects.
     * 
     * @var Registry
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
     * Default constructor, loads configuration from a config file 
     * 
     */
    private function __construct()
    { 
        $this->loadConfig();        
        
        // if profiler is activated, load microtime
        if ($this->get('enable_profiler'))
        {
        	Profiler::addLog('loading registry');
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
     * Retrieves the default registry instance.
     *
     * @return Registry
     * 
     */
    public static function getInstance()
    {
        if (self::$singleton === null) 
        {
            self::$singleton = new Registry();
        }

        return self::$singleton;
    }

	/**
	 * Loads the main config.ini.php file
	 *
	 * @access	private
	 * @return	array
	 */
	private function loadConfig()
	{
		$config = array();
		$file 	= __APP_PATH . '/config/config.ini.php';
		
		if ( file_exists($file))
		{
			require($file);
		}
		
		// check format of $config var
		if ( ! isset($config) OR ! is_array($config))
		{
			$config = array();
		}

		// and assign
		foreach ($config as $k=>$v)
		{
			$this->set($k, $v);
		}
	}
    
	/**
	 * It can be used in static environments
	 * @set undefined vars
	 *
	 * @param string $index
	 * @param mixed $value
	 *
	 * @return void
	 * 
	 * @access public
	 *
	 */
	public function set($index, $value)
	{
		$this->config[$index] = $value;
	}
	
	/**
	 * Alias for __set, used in dynamic environments
	 * @set undefined vars
	 *
	 * @param string $index
	 * @param mixed $value
	 *
	 * @return void
	 * 
	 * @access public
	 *
	 */
	public function __set($index, $value)
	{
		$this->set($index,$value);
	}

	/**
	 * @get variables
	 *
	 * @param mixed $index
	 *
	 * @return mixed
	 * 
	 * @access public
	 *
	 */
	public function get($index)
	{
		if (!isset($this->config[$index])) 
		{
			return null;
		}
	
	    return $this->config[$index];
	}
	
	/**
	 * Alias for __get, used in dynamic environments
	 * @get variables, null if undefined
	 *
	 * @param string $index
	 *
	 * @return mixed
	 * 
	 * @access public
	 *
	 */
	public function __get($index)
	{
		return $this->get($index);
	}
     
}

?>
