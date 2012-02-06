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
 * system/lib/language.class.php
 * 
 * Manages the entire localization and internationalization process
 * 
 * @category	System
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares <correo@jorgealbaladejo.com>
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @version		0.1a
 * @since		Version 0.1a
 */
class Language
{
	/**
     * Singleton object reference
     * 
     */
    private static $singleton = null;
	
	/**
	 * language code
	 *
	 */
	private $langCode = null;
	
	/**
	 * Default plural form
	 * 
	 */
	private $defaultPlural = '';
	
	/**
	 * Registry reference
	 *  
	 */
	private $registry = null;
	
	/**
	 * Flag for gettext available 
	 * 
	 */
	private $gettext = null; 
	
	/**
	 * Keeps the translation strings rom the correspondent file in memory
	 * 
	 */
	private $langBuffer = null;
	
	/**
	 * Constructor
	 *
	 */
	function __construct()
	{
		$this->registry = Registry::getInstance();
		
		$this->path		= $this->registry->get('lang_path');
		$this->domain	= $this->registry->get('lang_domain');
		$this->charset	= $this->registry->get('charset');
		$this->langCode = $this->registry->get('lang_code');		
	}
	
	/**
	 * The main destructor erases singleton object
	 * 
	 * @return void
	 */
	public function __destruct() 
	{  			
      	self::$singleton = null ;
  	}
	
	/**
	 * __clone private so nobody can clone the instance
	 *
	 * @access private
	 */
	private function __clone(){}
	
	/**
     * Retrieves the default router instance.
     *
     * @return Router
     * 
     */
    public static function getInstance()
    {
        if (self::$singleton === null) 
        {
            self::$singleton = new Language();
        }

        return self::$singleton;
    }
	
	/**
	 * Initializes gettext and locale settings
	 * 
	 * @param string $lang the locale code to be set
	 * @param string $domain the domain to bind translations to
	 *
	 * @return boolean the result of the operation
	 *
	 * @access public
	 * 
	 * @TODO: could not make gettext work on my server... further research needed
	 */
    public function initGetText($lang = '')
    {
		// variables used
		$localeSet 			= '';
		$bindtextdomainSet 	= '';
		$path				= $this->path;
		$domain				= $this->domain;
		$charset			= $this->charset;
		$result				= true;
		//	
		
		$this->setLangCode($lang);
	
		// environment variables
		if ( false == putenv("LANGUAGE=" . $this->langCode ) )
		{
		 	Profiler::error('LANGUAGE variable cannot be set to ' . $this->langCode);
			$result = false;
		}		
		if ( false == putenv("LANG=" . $this->langCode ) )
		{
		 	Profiler::error('LANG variable cannot be set to ' . $this->langCode);
			$result = false;
		}
		if ( false == putenv("LC_ALL=" . $this->langCode ) )
		{
		 	Profiler::error('LC_ALL variable cannot be set to ' . $this->langCode);
			$result = false;
		}
		
		// locale for php application
		$localeSet = setlocale(LC_ALL, $this->langCode, $this->langCode . '' . strtolower($charset), $this->langCode . '.' . strtoupper($charset));
				
		if ( $localeSet != $this->langCode || empty($localeSet) )
		{
		 	Profiler::error(sprintf("Tried: setlocale to '%s', but could only set to '%s'.", $this->langCode, $localeSet) );
			$result = false;
		}
		
		// binding text locale to this application's domain
		$bindtextdomainSet = bindtextdomain($domain, $path );
		
		if ( empty($bindtextdomainSet) )
		{
		 	Profiler::error(sprintf("Tried: bindtextdomain, '%s', to directory, '%s', but received '%s'", $domain, $path, $bindtextdomainSet) );
			$result = false;
		}
		
		bind_textdomain_codeset($domain, $charset);
		
		$textdomainSet = textdomain($domain);
		if ( empty($textdomainSet) )
		{
		 	Profiler::error(sprintf("Tried: set textdomain to '%s', but got '%s'",$domain, $textdomainSet) );
			$result = false;
		}
		
		// and return result
		$this->gettext = $result;
		
		return $result;
    }
	
	/**
	 * Setter for the langCode property
	 * 
	 * @param string $lang
	 * 
	 * @access public 
	 */
	public function setLangCode($lang = '')
	{
		if (in_array($lang,$this->registry->supported_langs))
		{
			$this->langCode = $lang;
		}
	}
	
	/**
	 * Receives a text to be translated through gettext functions.
	 *
 	 * @param 	string  $string				string to be translated.
	 * @param	mixed	$params				array of parameters
	 * @param 	int		$params['count']	number of elements for plural forms
	 * @param 	string  $params['caps']		capitalize mode
	 *
	 * @return string	translated string, or original if no translation found
	 *
	 */
	function translate($string = '', $params = array())
	{
		// vars
		$count = 1;
		
		// plural or singular?
		if (isset($params['count']))
		{
			if(abs($params['count'] != 1 ))
			{
				$count = abs($params['count']);
			}
		}
		
		if ($this->gettext)
		{
			$string = ngettext($string, $string, $count);
		}
		else
		{
			$string = $this->getFromFile($string, $count);
		}
		
		// capitalization
		if (isset($params['caps']))
		{
			switch ($params['caps'])
			{
				case 'first':
					$string = ucfirst($string);
					break;
				case 'words':
					$string = ucwords($string);
					break;
				case 'upper':
					$string = strtoupper($string);
					break;
				case 'none':
				default:
			}	
		}
		
		// apply vars?
		if (isset($params['vars']))
		{
			if (is_array($params['vars']))
			{
				$params['vars'] = array_merge(array($count),$params['vars']);
			}
			else
			{
				$params['vars'] = array($count,$params['vars']);
			}
		}
		else
		{
			$params['vars'] = array($count);
		}
		
		$string = vsprintf($string,$params['vars']);
				
		// and it's done!
		return $string;
	}
	
	/**
	 * Retrieves a translated string from a plain php file
	 * 
	 * @param string to translate
	 * @param int $count for plural forms
	 * 
	 * @return translated string
	 * 
	 * @access private
	 */
	private function getFromFile($string = '', $count = 1)
	{
		if (!$this->langBuffer)
		{
			$this->langBuffer = $this->readLangFile();
		}

		if (!$this->defaultPlural)
		{
			$this->defaultPlural = $this->loadDefaultPlural();
		}
		
		// the index does not exist
		if ( ! isset($this->langBuffer[$string]) )
		{
			return $this->defaultPlural($string, $count);
		}
		
		// the index is not an array
		if ( ! is_array( $this->langBuffer[$string] ) )
		{
			return $this->defaultPlural($this->langBuffer[$string], $count);
		}
		
		// given the array, the specified $count index exists
		if ( isset( $this->langBuffer[$string][$count] ) )
		{
			return $this->langBuffer[$string][$count];
		}
		
		// the $count index does not exist, but it is actually 1
		if ( abs(intval($count)) == 1)
		{
			return $string;
		}
		 
		// the count index does not exist, try with 'n' or '*'
		if ( isset( $this->langBuffer[$string]['n'] ) )
		{
			return $this->langBuffer[$string]['n'];
		}
		if ( isset( $this->langBuffer[$string]['*'] ) )
		{
			return $this->langBuffer[$string]['*'];
		} 
		
		// finally, try a default plural over the string
		return $this->defaultPlural($string, $count);
	}	
	
	/**
	 * Creates a default plural form for the element
	 * 
	 * @param string $string [optional]
	 * @param int    $count [optional]
	 * 
	 * @return string 
	 */
	private function defaultPlural( $string = '', $count = 1 )
	{
		if ( abs(intval($count)) == 1 )	
		{
			return $string;
		}
		
		if ( $this->defaultPlural )	
		{
			return $string . $this->defaultPlural; 
		}
		
		return $string . 's';
	}
	
	/**
	 * Loads the default plural form, first from the language file
	 *  and then from the config file
	 *  
	 * @return string 
	 * @access private 
	 */
	private function loadDefaultPlural()
	{
		if (isset($this->langBuffer['lang_default_plural']))
		{
			return $this->langBuffer['lang_default_plural'];
		}
		
		return $this->defaultPlural = $this->registry->get('lang_default_plural');
	}
	
	/**
	 * Reads the language file from disk
	 * 
	 * @return the $lang array
	 * 
	 * @access private
	 */
	private function readLangFile()
	{
		$file = __APP_PATH . '/lang/' . $this->langCode . '.php';
	
		if (file_exists($file))
		{
			include($file);
	
			return $lang;	
		}
		
		return null;
	}
}
?>