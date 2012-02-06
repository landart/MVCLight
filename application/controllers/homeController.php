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
 * application/controllers/homeController
 * 
 * Manages the home page and inner redirections
 * 
 */
Class HomeController Extends AppController 
{
	/**
	 * Main index, creates the home page
	 * 
	 * @access public
	 * 
	 * @see system/lib/baseController#index()
	 * 
	 * @return writes the HTML content 
	 */
	public function index()
	{
		$this->template->page('home/index');
	}
	
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
			case 'database':
				$this->ajaxDatabase();
				break;
			
			case 'get':
				$this->ajaxGet();
				break;
				
			case 'input':
				$this->ajaxInput();
				break;
				
			case 'language':
				$this->ajaxLanguage();
				break;
				
			case 'light':
				$this->ajaxLight();
				break;
				
			case 'mvc':
				$this->ajaxMvc();
				break;
				
			case 'profiler':
				$this->ajaxProfiler();
				break;
			
			case 'session':
				$this->ajaxSession();
				break;
				
			case 'template':
				$this->ajaxTemplate();
				break;
				
			default:
		}
		
		return;
	}
	
	/**
	 * MVC section ajax response
	 * 	This method is private and cannot be accessed
	 *  directly from the url
	 * 
	 * @return writes the section html to the browser 
	 */
	private function ajaxMvc()
	{
		$this->template->show('home/ajax/mvc');
	}
	
	/**
	 * Lightweight section ajax response
	 * 	This method is private and cannot be accessed
	 *  directly from the url
	 * 
	 * @return writes the section html to the browser 
	 */
	private function ajaxLight()
	{
		$this->template->show('home/ajax/light');
	}
	
	/**
	 * Get section ajax response
	 * 	This method is private and cannot be accessed
	 *  directly from the url
	 * 
	 * @return writes the section html to the browser 
	 */
	private function ajaxGet()
	{
		$this->template->show('home/ajax/get', array( 'params' => array( $this->input->get('param1'), $this->input->get('param2') ) ) );
	}
	
	/**
	 * Templating section ajax response
	 * 	This method is private and cannot be accessed
	 *  directly from the url
	 * 
	 * @return writes the section html to the browser 
	 */
	private function ajaxTemplate()
	{
		$this->template->show('home/ajax/template');
	}
	
	/**
	 * Input section ajax response
	 * 	This method is private and cannot be accessed
	 *  directly from the url
	 * 
	 * @return writes the section html to the browser 
	 */
	private function ajaxInput()
	{
		$this->template->show('home/ajax/input');
	}
	
	/**
	 * Session section ajax response
	 * 	This method is private and cannot be accessed
	 *  directly from the url
	 * 
	 * @return writes the section html to the browser 
	 */
	private function ajaxSession()
	{
		$this->template->show('home/ajax/session');
	}
	
	/**
	 * Database section ajax response
	 * 	This method is private and cannot be accessed
	 *  directly from the url
	 * 
	 * @return writes the section html to the browser 
	 */
	private function ajaxDatabase()
	{
		$this->model('users');
		
		$this->template->show('home/ajax/database',array('users'=>$this->users->getAll()));
	}
	
	/**
	 * Profiler section ajax response
	 * 	This method is private and cannot be accessed
	 *  directly from the url
	 * 
	 * @return writes the section html to the browser 
	 */
	private function ajaxProfiler()
	{
		$this->template->show('home/ajax/profiler');
	}
	
	/**
	 * Language section ajax response
	 * 	This method is private and cannot be accessed
	 *  directly from the url
	 * 
	 * @return writes the section html to the browser 
	 */
	private function ajaxLanguage()
	{
		$this->template->show('home/ajax/language');
	}
}
?>
