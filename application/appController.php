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
 * application/appController.php
 * 
 * This file defines the application's parent class controlller
 * 
 */
Abstract Class appController Extends baseController 
{
	/**
	 * Main constructor, initializes the session attribute
	 * 
	 * @return void
	 * 
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load language!
		$this->language->initGetText( $this->session->get('language') );
	}
}
?>
