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
 * system/lib/cookie.class.php
 * 
 * Cookie handler, manages all relative to client cookie
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
Class Cookie
{	
	/**
	 * Reference to singleton instance
	 *
	 * @access private
	 */
	private static $singleton = null;
	
	/**
	 * Reference to registry object
	 *
	 * @access private
	 */
	private $registry = null;
	
	/**
	 * Loads registry
	 * 
	 * @access public
	 */
	private function __construct()
	{
		$this->registry = Registry::getInstance();
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
	 * Return DB instance or create intitial connection
	 * 
	 * @return Db object
	 *
	 * @access public
	 */
	public static function getInstance() 
	{
		if (!self::$singleton) 
		{
	   		self::$singleton = new Cookie();
		}
		
		return self::$singleton;
	}
	
	/**
	 * Getter for variables in cookies
	 * 
	 * @param string $var
	 * 
	 * @return mixed the cookie variable value
	 * 
	 */	
	public function get($var = '')
	{
		return $this->registry->input->cookie($var);
	}
	
	/**
	 * Setter for variables in cookies
	 * 
	 * @param string $variable name
	 * @param mixed $value the value to store
	 * @param int $time
	 * 
	 * @return boolean
	 * 
	 */
	public function set($var, $value = null, $time = null)
	{
		$time = intval($time);
		
		if ($time <= 0)
		{
			$time = time()-3600;
		}
		
		return setcookie($var, $value, $time, "/");
	}
	
	/**
	 * Erases a cookie's content 
	 * 
	 * @return void
	 */
	public function erase()
	{
		$this->set("bc56", "");
		$this->set("tq32", "");
		$this->set("bx44", "");
	}
}

?>
