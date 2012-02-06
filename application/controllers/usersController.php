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
 * application/controllers/usersController
 * 
 * Manages the log in and out ajax calls
 * 
 */
Class UsersController Extends AppController 
{
	/**
	 * Main index, no action
	 * 
	 * @access public
	 * 
	 * @see system/lib/baseController#index()
	 * 
	 * @return void 
	 */
	public function index(){}
	
	/**
	 * AJAX dispatcher, checks the action parameter and 
	 * 	executes the private method
	 * 
	 * @paaram $_GET['action'];
	 * 
	 * @access public 
	 * 
	 * @return void
	 */
	public function ajax()
	{
		switch ( $this->input->get('action') )
		{
			case 'login':
				$this->ajaxLogin();
				break;
			
			case 'logout':
				$this->ajaxLogout();
				break;
						
			default:
		}
		
		return;
	}
	
	/**
	 * Login ajax response
	 * 	This method is private and cannot be accessed
	 *  directly from the url
	 * 
	 * @return writes the session section content 
	 */
	private function ajaxLogin()
	{
		$this->session->login(rand(0,1000));
		
		$this->template->show('home/ajax/session');
	}
	
	/**
	 * Logout ajax response
	 * 	This method is private and cannot be accessed
	 *  directly from the url
	 * 
	 * @return writes the session section content 
	 */
	private function ajaxLogout()
	{
		$this->session->logout();
		
		$this->template->show('home/ajax/session');
	}
}
?>
