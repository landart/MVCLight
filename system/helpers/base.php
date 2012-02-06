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
 * @category	Helpers
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.1a
 * @since		Version 0.1a
 */

/**
 * system/helpers/base.php
 * 
 * Contains some basic functions needed for bootstrapping the framework
 *  	and to perform primary actions
 *  
 * @category	Helpers
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.1a
 * @since		Version 0.1a
 */

/**
 * Debug function
 * Overloads print_r to have color and indentation on complex variables
 * 
 * @param mixed $variable to strip and show
 * @param boolean $return the result instead of echoing
 * 
 */
if (!function_exists('_debug'))
{
	function _debug($variable = null, $return = false)
	{
		// never show debug messages in production mode
		if (PRODUCTION_SERVER)
		{
			return false; 
		}
		 
		$variable = str_replace(' ','&nbsp;',print_r($variable, TRUE));
		$variable = str_replace("\n\r","\n",$variable);
		$variable = str_replace("\n",'<br />',$variable);
		$variable = str_replace('[','<strong style="color:black">[',$variable);
		$variable = str_replace(']',']</strong>',$variable);
		$variable = str_replace('=>','<span style="color:green">=></span>',$variable);
		$variable = str_replace('(','<span style="color:red">(</span>',$variable);
		$variable = str_replace(')','<span style="color:red">)</span>',$variable);
		
		// format final result
		$variable = '<div style="font-family:Verdana, Arial, Helvetica, Sans Serif;font-size:0.8em; color: blue">' . $variable . '</div>';
		
		if ($return)
		{
			return $variable;
		}
		// otherwise
		echo $variable;
	}
}

/**
 * Creates a redirection based on current base url
 * 
 * @param string $path
 * 
 * @return redirects user browser
 */
if (!function_exists('redirect'))
{
	function redirect($path = '')
	{
		// is it a regular url?
		if (strpos($path,'http') === 0){
			header ('Location: ' . $path);
			exit(0);
		}
		
		// resources needed
		$input = Input::getInstance();
		
		// filter path to add a slash
		if (strpos($path,'/') === false)
		{
			$path = '/';
		}
		else
		{
			if (strpos($path,'/') !== 0)
			{
				$path = '/' . $path;
			}
		}
		
		header( 'Location: ' . baseUrl()  . $path ) ;
		
		exit (0);
	}
}

/**
 * Returns the application's base url to be used in views
 * 
 * @return string base url 
 */
if (!function_exists('baseUrl'))
{
	function baseUrl()
	{
		return __BASEURL;
	}
}

/**
 * Returns the servers's full base url
 * 
 * @return string base url 
 */
if (!function_exists('baseFullUrl'))
{
	function baseFullUrl()
	{
		return 'http://' . $_SERVER['HTTP_HOST'] . baseUrl();
	}
}

/**
 * Alias for Language::translate() function.
 * This function receives a string to be translated, and passes it Language library for translation.

 * @param 	string  $string				string to be translated.
 * @param	mixed	$params				array of parameters
 * @param 	int		$params['count']	number of elements for plural forms
 * @param 	string  $params['caps']		capitalize mode
 *
 * @return the translated text
 *
 */
if ( ! function_exists('__') )
{
	function __($string = '', $params = NULL)
	{
		if ($string)
		{
			$registry = Registry::getInstance();
			
			return($registry->language->translate($string, $params));
		}
	}
}

/**
 * Filters an string html input to remove line breaks and extra spaces
 * 
 * @param string $html
 * 
 * @return string 
 */
if ( ! function_exists('reduce') )
{
	function reduce($html = '')
	{
		return ( str_replace('  ',' ', 
					str_replace("\t",'', 
						str_replace("\n",'',$html) ) 
					)
				);
	}
}	
?>