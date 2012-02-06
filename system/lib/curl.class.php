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
 * @since		Version 0.2a
 */

/**
 * system/lib/curl.class.php
 * 
 * Handles curl connections
 * 
 * @category	System
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.2a
 * @since		Version 0.2a
 */
Class Curl
{	
	/**
	 * StdClass $this reference
	 * 
	 * @var Curl
	 * @access private 
	 */
	private static $singleton = null;	
	
	/**
	 * Reference to the registry object
	 * 
	 * @var Registry
	 * @access unknown_type
	 */
	private $registry = null;
	
	/**
	 * References the curl initialization
	 * 
	 * @var object
	 * @access private
	 */
	private $curl = null;
	
	/**
	 * User for url authentication
	 * 
	 * @var string
	 * @access private
	 */
	private $user = '';
	
	/**
	 * User for url authentication
	 * 
	 * @var string
	 * @access private
	 */
	private $pass = '';
	
	/**
	 * Loads registry and inits curl connection
	 * 
	 * @param string $user for authenticated urls
	 * @param string $pass for authentication
	 * 
	 * @access private
	 * @return void
	 */
	private function __construct($user = '', $pass = '')
	{
		$this->registry = Registry::getInstance();
		
		// init and configure curl
		$this->curl = curl_init();
		
		$this->user = $user;
		$this->pass = $pass;
	}
	
 	/**
	 * The main destructor closes database connection
	 * 	and initializes the DB singleton object
	 * 
	 * @return void
	 */
	public function __destruct() 
	{
		curl_close(self::$singleton->curl);
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
	   		self::$singleton = new Curl();
		}
		
		return self::$singleton;
	}
	
	/**
	 * Launches a get request through curl
	 * 
	 * @param array $params and configurations
	 * @param string $params['url'] to get 
	 * 
	 * @return string the result of the request
	 * @access public 
	 */
	public function getRequest($data = array())
	{
		if (!$this->initRequest($data))
		{
			return false;
		}
		
		// finally, send
		return curl_exec($this->curl);
	}
	
	/**
	 * Launches a post request through curl
	 * 
	 * @param array $data to be sent
	 * @param string $data['url'] to send the data to 
	 * 
	 * @return string the result of the request
	 * @access public 
	 */
	public function postRequest($data = array())
	{
		if (!$this->initRequest($data))
		{
			return false;
		}
			
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
		
		// finally, send
		return curl_exec($this->curl);
	}
	
	/**
	 * Internal initialization of the curl request
	 * 
	 * @param array $data passed as reference
	 * @param array $data['url]
	 * 
	 * @return boolean
	 * @access private 
	 */
	private function initRequest(&$data = array())
	{
		if (!isset($data['url']))
		{
			return false;
		}
		
		if (strpos($data['url'],'http://')===false)
		{
			$data['url'] = baseFullUrl() . $data['url'];
		}
		
		curl_setopt($this->curl, CURLOPT_URL, $data['url']);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_HEADER, 1);
		
		if ($this->user)
		{
			curl_setopt($this->curl, CURLOPT_USERPWD, $this->user . ':' . $this->pass);	
		}
		
		unset($data['url']);
		
		return true;
	}
}

?>
