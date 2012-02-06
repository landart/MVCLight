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
 * system/lib/db.class.php
 * 
 * The database class, used in singleton mode
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
class Db
{
	/**
	 * StdClass $this reference
	 * 
	 * @access private 
	 */
	private static $singleton = null;	

	/**
	 * Database connection link
	 * 
	 * @access private
	 */
	private $link = null;
	
	/**
	 * Database user
	 * 
	 * @access private
	 */
	private $user = null;
	
	/**
	 * Database password
	 * 
	 * @access private
	 */
	private $pass = null;
	
	/**
	 * Database to use
	 * 
	 * @access private
	 */
	private $base = null;
	
	/**
	 * Database host
	 * 
	 * @access private
	 */
	private $host = null;
	
	/**
	 * Reference to the registry object
	 * 
	 * @access unknown_type
	 */
	private $registry = null;
	
	/**
	 * Allows easy switching between mysql and pdo modes, for
	 * 	backwards compatiiblity's sake
	 * 
	 * @access private
	 */
	private $pdo  = false;
	
	/**
	 * Shows or hides SQL errors (for production environments)
	 * 	This setting is got from the database config file
	 * 
	 * @access private
	 */
	private $errors = false;
	
	/**
	 * Register to keep the last executed query
	 * 
	 * @access private
	 */
	private $lastQuery = '';
	
	/**
	 * Constructor, initializes the connection and sets the singleton pattern
	 * 
	 * @return void
	 */
	private function __construct()
	{
		$conf	= array();
		$file 	= __APP_PATH . '/config/db.ini.php';
		
		if ( ! file_exists($file) )
		{
			return false;
		}		
		
		require($file);
	
		// check format
		if ( ! isset($conf) OR ! is_array($conf))
		{
			$conf = array();
		}

		$this->user	= $conf['user'];
		$this->pass	= $conf['pass'];
		$this->base	= $conf['base'];
		$this->host	= $conf['host'];
		$this->errors = $conf['errors'];
		
		$this->registry = Registry::getInstance();
				
		if ($this->pdo)
		{
			$this->connectPdo();
		}
		else
		{
			$this->connectMysql();
		}
	}
	
	/**
	 * The main destructor closes database connection
	 * 	and initializes the DB singleton object
	 * 
	 * @return void
	 */
	public function __destruct() 
	{
  		if (!is_null(self::$singleton->link)) 
  		{
  			if (!self::$singleton->pdo)
  			{
      			mysql_close(self::$singleton->link) ;
  			}
  			
      		self::$singleton->link = null ;
      		self::$singleton = null ;
    	}
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
	   		self::$singleton = new Db();
		}
		
		return self::$singleton;
	}
		
	/**
	 * Creates a Mysql connection with old driver (faster)
	 * 
	 * @return void
	 */
	private function connectMysql()
	{
		if (! ($tmp = mysql_connect($this->host,$this->user, $this->pass)))
		{
			if ($this->registry->get('enable_profiler'))
			{
				Profiler::sqlError(mysql_error());	
				return;
			}
			else
			{
				throw new Exception('Connection impossible : ' . mysql_error());
			}
		}
		
  		$this->link = &$tmp ;
  		
  		// Select the db
 		if (!mysql_select_db($this->base,$this->link))
 		{
    		if ($this->registry->get('enable_profiler'))
			{
				Profiler::sqlError(mysql_error($this->link));
				return;	
			}
			else
			{
				throw new Exception('Impossible to select the database : ' . mysql_error($this->link));
			}
 		}  
	}
	
	/**
	 * Creates a PDO Mysql connection (slower but more powerful)
	 * 
	 * @return void
	 * 
	 * @access private
	 */
	private function connectPdo()
	{
		$this->link = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->base, $this->user, $this->pass);
	    $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	/**
	 * Sets PDO mode
	 * 
	 * @return void
	 * 
	 * @access public
	 */
	public function setPdo()
	{
		$this->pdo 	= true;
		
		$this->connectPdo();
	}
	
	/**
	 * Unsets PDO mode
	 * 
	 * @return void
	 * 
	 * @access public
	 */
	public function unsetPdo()
	{
		$this->pdo  	= false;
		
		$this->connectMysql();
	}
		
	/**
	 * Select Queries, return an array with all results
	 * 
	 * @param string $req
	 * 
	 * @return mixed array with results
	 * 
	 * @access public
	 */
    public function read($req = '')
    {
    	// vars
    	$start 	= microtime(true);
    	$return = null;
    	
    	$this->lastQuery = $req;
    	
		// @TODO: this piece of code is not working (PDO)
     	if ($this->pdo)
    	{
    		$out 	= $this->link->query($req);
    		$return = $out->fetchAll();
    		
			if (!$out && $this->errors)
    		{
	    		if (! $this->registry->get('production_server'))
	    		{
	    			if ($this->registry->get('enable_profiler'))
		    		{
			    		Profiler::sqlError($req);
						return false;	
			    	}	
			    	else
				    {
				    	throw new Exception('Invalid query: ' . $req);
				    }	
	    		}	    	
			}
			
    		return $out->fetchAll();
    	}
    	else
    	{
    		$rows	= mysql_query($req, $this->link);	
    		
    		if (!$rows && $this->errors)
    		{
    			if (!$this->registry->get('production_server'))
    			{
    				if ($this->registry->get('enable_profiler'))
			    	{
			    		Profiler::sqlError($req);	
						return false;
			    	}
			    	else
			    	{
			    		throw new Exception('Invalid query: ' . $req);
			    	}			    	
    			}
    		} 
    			
					
			$out 	= array();
			
			if ($rows)
			{
				while( $row = mysql_fetch_array($rows,MYSQL_ASSOC) )
		   		{
		        	$out[] = $row;
		    	}
    		}
    		
    		if (!$this->registry->get('production_server'))
    		{
    			if ($this->registry->get('enable_profiler'))
    		   	{
	    			Profiler::query($req, $start);	
	    		}
    		}
    	
	    	return $out;	    	
    	}
    	
    }
 
    /**
     * Insert and Update queries, return numbers of affected rows
     * 
     * @param string $req
     * 
     * @return int number of affected rows
     * 
     * @access public 
     */
    public function write($req = '')
    {
    	$start 	= microtime(true);
    	
    	$this->lastQuery = $req;
    	
		// @TODO: this piece of code is not working (PDO)
    	if ($this->pdo)
    	{
    		$out = $this->link->query($req);
    		
			if (!$out && $this->errors)
    		{
	    		if (!$this->registry->get('production_server'))
	    		{
	    			if ($this->registry->get('enable_profiler'))
		    		{
			    		Profiler::sqlError($req);	
						return false;
			    	}	
			    	else
				    {
				    	throw new Exception('Invalid query: ' . $req);
				    }	
	    		}	
			}
			
    		return $out->rowCount();
    	}
    	else
    	{
    		$rows = mysql_query($req,$this->link);
    		
    		if (!$rows && $this->errors) 
    		{
    			if (!$this->registry->get('production_server'))
    			{
    				if ($this->registry->get('enable_profiler'))
			    	{
			    		Profiler::sqlError($req);	
						return false;
			    	}
			    	else
			    	{
			    		throw new Exception('Invalid query: ' . $req);
			    	}			    	
    			}
    		}
    		
    		if (!$this->registry->get('production_server'))
    		{
    			if ($this->registry->get('enable_profiler'))
    		   	{
	    			Profiler::query($req, $start);	
	    		}
    		}
    	
    		return mysql_affected_rows($this->link);
    	}
    }	
    
    /**
     * Gets the last inserted ID
     * 
     * @return mixed the last inserted ID
     */
    public function lastInsertId()
    {
    	if ($this->pdo)
    	{
    		return $this->link->lastInsertId();
    	}
    	else
    	{
    		return mysql_insert_id($this->link); 	
    	}
    }
    
    /**
     * Returns the last executed query
     * 
     * @return string
     * 
     * @access public
     */
    public function lastQuery()
    {
    	return $this->lastQuery;
    }
   
} /*** end of class ***/

?>
