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
 * system/lib/session.class.php
 * 
 * Session handler, manages all relative to user session,
 *  authentication and information
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
Class Session
{	
	/**
	 * @var StdClass $this reference
	 * 
	 * @access private
	 */
	private static $singleton 	= null;	
	
	/**
	 * Logged in status
	 * 
	 * @access private
	 */
	private $logged 		= false;
	
	/**
	 * Reference to registry
	 *
	 * @access private
	 */
	private $registry			= null;
	
	/**
	 * Starts session
	 * 
	 * @access public
	 */
	private function __construct()
	{
		$this->registry = Registry::getInstance();
		$this->startSession();
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
	 * Return an instance for singleton static use
	 *
	 * @return Session object
	 *
	 * @access public
	 *
	 */
	public static function getInstance() 
	{
		if (!self::$singleton) 
		{
	   		self::$singleton = new Session();
		}
		
		return self::$singleton;
	}
	

	/**
	 * Getter for variables in session
	 * 
	 * @param string the session variable
	 * 
	 * @return mixed the session variable
	 */
	public function get($var = '')
	{		
		if (isset($_SESSION[$var]))
		{
			if ($_SESSION[$var] != '' && $_SESSION[$var] != null)
			{
				return $_SESSION[$var];	
			} 			
		}
		
		return null;
	}
	
	/**
	 * Getter for flash variables in session
	 * 
	 * @param string the session variable
	 * 
	 * @return mixed the session variable
	 */
	public function getFlash($var = '')
	{		
		$var 	= 'flash:' . $var;
		
		$flash 	= $this->get($var);
		
		$this->delete($var);
		
		return $flash;
	}
	
	/**
	 * Sets a session flash variable
	 * 
	 * @param string $var name
	 * @param mixed $value for the variable
	 * 
	 * @return void
	 */
	public function setFlash($var, $value = null)
	{
		$this->set('flash:'.$var, $value);	
	}
	
	/**
	 * Sets a session variable
	 * 
	 * @param string $var name
	 * @param mixed $value for the variable
	 * 
	 * @return void
	 */
	public function set($var, $value = null)
	{
		$_SESSION[$var] = $value;	
	}
	
	/**
	 * Deletes a session variable
	 * 
	 * @param string $var name
	 *
	 */
	public function delete($var)
	{
		if (isset($_SESSION[$var]))
		{
			unset($_SESSION[$var]);
		}
	}
	
	/**
	 * Gets a cookie variable
	 * 	connects with static class Cookie
	 * 
	 * @param string $var name
	 * 
	 * @return mixed the cookie variable
	 */
	private function getCookie($var = '')
	{
		return $this->registry->cookie->get($var);
	}
	
	/**
	 * Sets a cookie variable
	 *  connects with static class Cookie
	 *  
	 * @param string $var name
	 * @param mixed $value for the variable
	 * @param int $time cookie validity
	 * 
	 * @return void
	 */
	private function setCookie($var, $value = null, $time = null)
	{
		return $this->registry->cookie->set($var, $value, $time);
	}
	
	/**
	 * Starts a PHP session and checks cookie
	 * 
	 * @return void
	 */
	private function startSession()
	{
		if (session_id() == "")
		{
			session_start();
		}
		
		// session already exists, the user is logged in
		if ( $this->get('id') )
		{
	    	$this->logged = true;
    	}
    	// session does not exist, try to load it from cookie
		else
    	{
    		// values in cookie exist
			// TODO: add security and encryption features
    		if ( $this->getCookie('bc56') )
    		{
				$this->set('id',$this->getCookie('bc56'));
						
				// marked as logged in
				$this->logged = true;
			}
			// no cookie
			else
			{
				$this->logged = false;
			}
		}
	}
	
	/**
	 * Erases a cookie content
	 *  connects with static class Cookie
	 *  
	 * @return void
	 */
	private function eraseCookie()
	{
		$this->registry->cookie->erase();
	}
	
	/**
	 * Return true if a user is logged in
	 * 
	 * @return boolean
	 */
	public function isLogged()
	{
		return $this->logged;    	
	}
	
	/**
	 * Performs the login actions related to session
	 * 
	 * @param string $user id
	 * @param boolean $rememberme
	 * 
	 * @return void 
	 */
	public function logIn($uid, $rememberme = false)
	{
		$this->set('id',$uid);
		
		$this->logged = true;
   						
   		if($rememberme)
		{
			$this->setCookie('bc56', md5($uid), time()+60*60*24*30, '/');
		}
	}
	
	/**
	 * Logs user out by destroying session
	 * 
	 * @access public
	 */
	public function logout()
	{
		session_destroy();
		
		$this->logged = false;
		
		$this->setCookie('bc56', '', time()-3600, '/');
	}
	
	/**
	 * Returns the user role
	 *  This method must be implemented on a child library
	 *  The role convention and variable to store it depends on the application
	 *  
	 * @return mixed the user role
	 * 
	 * @access public
	 */
	public function getUserRole(){}

}

?>
