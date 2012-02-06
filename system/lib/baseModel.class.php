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
 * @category	Models
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.1a
 * @since		Version 0.1a
 */

/**
 * system/lib/baseModel.class.php
 * 
 * Abstract parent base model
 * 
 * @category	Models
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.1a
 * @since		Version 0.1a
 */
Abstract Class baseModel extends baseObject
{
	/**
	 * Database object
	 * 
	 * @access protected
	 * 
	 */
	protected $db = null;
	
	/**
	 * Table name for this model
	 * 
	 * @access protected
	 * 
	 */
	protected $table = '';
	
	/**
	 * Loads a database instance
	 * 
	 * @return void
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// if it was not autoloaded...
		if (!$this->registry->db)
		{
			$this->registry->db	= Db::getInstance();
		}
		
		$this->db = $this->registry->db;
		
		if (!$this->table)
		{
			$this->table = str_replace('Model','',get_class($this));
		}
	}
}

?>
