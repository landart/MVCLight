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
 * application/controllers/languageController
 * 
 * Manages the language selection
 * 
 */
Class LanguageController Extends AppController 
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
			case 'change':
				$this->ajaxChange();
				break;
									
			default:
		}
		
		return;
	}
	
	/**
	 * Changes the current language of the website.
	 * AJAX response
	 *
	 * @param $_GET['new_lang'] new language identifier
	 *
	 */
	private function ajaxChange()
	{
		// input
		$newLang 	 = $this->input->get('new_lang'); 
				
		// initial variables
		$supportedLangs 	= $this->registry->get('supported_langs');
		$defaultLang 		= $this->registry->get('lang_code');
		$defaultCode		= substr($defaultLang,0,2);
		$redirection 		= '';
		//
		
		if (!in_array($newLang, $supportedLangs))
		{
			$newLang = $defaultLang;
		}
				
		$this->session->set('language',$newLang);
		$this->registry->language->setLangCode($newLang);
		
		$this->template->show('home/ajax/language');
	}
}
?>
