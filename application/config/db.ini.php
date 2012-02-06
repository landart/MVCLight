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
 * application/config/db.ini.php
 * 
 * This file contains the application's database configuration
 * 
 */

 // all configurations are in this array, it is easier to process later
 $_db = array(	'localhost'		=> array('host' 	=> 'localhost',
										 'user'		=> 'root',
										 'pass' 	=> '',
										 'base' 	=> 'mvclight',
										 'errors'	=> true) );

 $_server = $_SERVER['SERVER_NAME'];
 
 // do not allow values out of the configured ones
 if (!in_array($_server,array_keys($_db)))
 {
	$_server = 'localhost';
 }
 
 // finally, assign the values to $conf
 if (isset($_db[$_server]))
 {
	$conf = $_db[$_server];		
 }
?>