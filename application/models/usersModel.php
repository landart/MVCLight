<?php
/**
 * MVCLight
 *
 * An open source application development framework for PHP
 *
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @since		Version 0.1a
 * @filesource
 */

/**
 * application/models/UsersModel
 * 
 * Model for users
 * 
 */

class UsersModel extends AppModel 
{
	/**
	 * Constructor, use it to load the database interface
	 * 	if db_auto_load is disabled
	 * 
	 * @return void
	 * 
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();
		
		// if it was not autoloaded...
		if (!$this->db)
		{
			$this->db = Db::getInstance();
		}
	}
	
   	/** 
   	 * Gets all users from database
	 * 
	 * @return array of users
	 * 
	 * @access public
	 */
   	public function getAll()
   	{
   		$res = $this->db->read('SELECT * FROM ' . $this->table);
		$ret = array();
				
		foreach($res as $r)
		{
			$ret[$r['userID']] = $r;
		}	
	
		return $ret;			
	}	
}

?>